<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Test\Unit\ViewModel;

use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Panth\WhatsApp\Helper\Data;
use Panth\WhatsApp\ViewModel\Category;
use Panth\WhatsApp\ViewModel\FloatButton;
use Panth\WhatsApp\ViewModel\Product;
use PHPUnit\Framework\TestCase;

class UrlEncodingTest extends TestCase
{
    /**
     * @param array<string, mixed> $returns
     */
    private function helper(array $returns): Data
    {
        $helper = $this->createStub(Data::class);
        foreach ($returns as $method => $value) {
            $helper->method($method)->willReturn($value);
        }

        return $helper;
    }

    private function registry(string $key, ?DataObject $value): Registry
    {
        $registry = $this->createStub(Registry::class);
        $registry->method('registry')->willReturnCallback(
            static fn(string $requested) => $requested === $key ? $value : null
        );

        return $registry;
    }

    public function testFloatUrlEncodesSpacesAsPercentTwentyNotPlus(): void
    {
        $url = (new FloatButton($this->helper([
            'getWhatsAppPhone' => '+91 84012 70422',
            'getWhatsAppMessage' => "Hi there #1 100% sure\nthanks",
        ])))->getWhatsAppUrl();

        $this->assertSame('https://wa.me/918401270422?text=Hi%20there%20%231%20100%25%20sure%0Athanks', $url);
        $this->assertStringNotContainsString('+', $url);
    }

    public function testFloatUrlEncodesMultibyteText(): void
    {
        $url = (new FloatButton($this->helper([
            'getWhatsAppPhone' => '1',
            'getWhatsAppMessage' => "Caf\u{e9}",
        ])))->getWhatsAppUrl();

        $this->assertSame('https://wa.me/1?text=Caf%C3%A9', $url);
    }

    public function testProductTemplateWithoutPlaceholdersIsSentAsIs(): void
    {
        $product = new DataObject(['id' => 4, 'name' => 'Bag', 'product_url' => 'https://example.test/bag.html']);
        $vm = new Product(
            $this->helper(['getWhatsAppPhone' => '12', 'getWhatsAppProductMessageTemplate' => 'Call me']),
            $this->registry('current_product', $product)
        );

        $this->assertSame('https://wa.me/12?text=Call%20me', $vm->getWhatsAppUrl());
    }

    public function testProductPlaceholdersRepeatAndEncodeQueryCharacters(): void
    {
        $product = new DataObject([
            'id' => 4,
            'name' => 'Tee & Cap',
            'product_url' => 'https://example.test/tee.html?color=red&size=m',
        ]);
        $vm = new Product(
            $this->helper([
                'getWhatsAppPhone' => '12',
                'getWhatsAppProductMessageTemplate' => '{product_name}/{product_name} {product_url}',
            ]),
            $this->registry('current_product', $product)
        );

        $this->assertSame(
            'https://wa.me/12?text=Tee%20%26%20Cap%2FTee%20%26%20Cap%20'
            . 'https%3A%2F%2Fexample.test%2Ftee.html%3Fcolor%3Dred%26size%3Dm',
            $vm->getWhatsAppUrl()
        );
    }

    public function testProductWithoutUrlLeavesPlaceholderEmpty(): void
    {
        $vm = new Product(
            $this->helper([
                'getWhatsAppPhone' => '12',
                'getWhatsAppProductMessageTemplate' => '{product_name}:{product_url}',
            ]),
            $this->registry('current_product', new DataObject(['id' => 4, 'name' => 'Bag']))
        );

        $this->assertSame('https://wa.me/12?text=Bag%3A', $vm->getWhatsAppUrl());
    }

    public function testCategoryUrlStripsPhoneFormattingAndEncodesName(): void
    {
        $vm = new Category(
            $this->helper(['getWhatsAppPhone' => '(020) 7946-0000', 'getWhatsAppCategoryMessageTemplate' => 'Help']),
            $this->registry('current_category', new DataObject(['name' => 'Bags/Totes']))
        );

        $this->assertSame(
            'https://wa.me/02079460000?text=' . rawurlencode('Help (Current category: Bags/Totes)'),
            $vm->getWhatsAppUrl()
        );
    }

    public function testCategoryUrlStillBuiltWhenPhoneEmpty(): void
    {
        $vm = new Category(
            $this->helper(['getWhatsAppPhone' => '', 'getWhatsAppCategoryMessageTemplate' => 'Help']),
            $this->registry('current_category', null)
        );

        $this->assertSame('https://wa.me/?text=Help', $vm->getWhatsAppUrl());
    }
}
