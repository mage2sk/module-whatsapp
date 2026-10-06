<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Test\Unit\ViewModel;

use Magento\Catalog\Model\Product as CatalogProduct;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Panth\WhatsApp\Helper\Data;
use Panth\WhatsApp\ViewModel\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @param array<string, mixed> $returns
     * @param mixed $product
     */
    private function viewModel(array $returns, $product = null): Product
    {
        $helper = $this->createStub(Data::class);
        foreach ($returns as $method => $value) {
            $helper->method($method)->willReturn($value);
        }
        $registry = $this->createStub(Registry::class);
        $registry->method('registry')->willReturnCallback(
            static fn(string $key) => $key === 'current_product' ? $product : null
        );

        return new Product($helper, $registry);
    }

    public function testEnabledRequiresGlobalAndProductFlags(): void
    {
        $this->assertTrue($this->viewModel([
            'isWhatsAppEnabled' => true,
            'isWhatsAppProductEnabled' => true,
        ])->isEnabled());
        $this->assertFalse($this->viewModel([
            'isWhatsAppEnabled' => false,
            'isWhatsAppProductEnabled' => true,
        ])->isEnabled());
        $this->assertFalse($this->viewModel([
            'isWhatsAppEnabled' => true,
            'isWhatsAppProductEnabled' => false,
        ])->isEnabled());
    }

    public function testGetProductReadsCurrentProductFromRegistry(): void
    {
        $product = new DataObject(['id' => 3]);

        $this->assertSame($product, $this->viewModel([], $product)->getProduct());
    }

    public function testUrlIsEmptyWithoutProduct(): void
    {
        $this->assertSame('', $this->viewModel(['getWhatsAppPhone' => '123'])->getWhatsAppUrl());
    }

    public function testUrlIsEmptyForProductWithoutId(): void
    {
        $vm = $this->viewModel(['getWhatsAppPhone' => '123'], new DataObject(['name' => 'Shoe']));

        $this->assertSame('', $vm->getWhatsAppUrl());
    }

    public function testUrlFillsTemplatePlaceholders(): void
    {
        $product = new DataObject([
            'id' => 9,
            'name' => 'Red Shoe',
            'product_url' => 'https://example.test/red-shoe.html',
        ]);
        $vm = $this->viewModel([
            'getWhatsAppPhone' => '+1-555-0100',
            'getWhatsAppProductMessageTemplate' => 'About {product_name}: {product_url}',
        ], $product);

        $this->assertSame(
            'https://wa.me/15550100?text=' . rawurlencode('About Red Shoe: https://example.test/red-shoe.html'),
            $vm->getWhatsAppUrl()
        );
    }

    public function testUrlUsesFallbackNameAndTemplate(): void
    {
        $vm = $this->viewModel([
            'getWhatsAppPhone' => '42',
            'getWhatsAppProductMessageTemplate' => '',
        ], new DataObject(['id' => 1]));

        $this->assertSame(
            'https://wa.me/42?text=' . rawurlencode("Hi! I'm interested in this product"),
            $vm->getWhatsAppUrl()
        );
    }

    public function testUrlFallsBackWhenProductAccessorsThrow(): void
    {
        $product = $this->createStub(CatalogProduct::class);
        $product->method('getId')->willReturn(5);
        $product->method('getName')->willThrowException(new \RuntimeException('broken'));
        $vm = $this->viewModel([
            'getWhatsAppPhone' => '7',
            'getWhatsAppProductMessageTemplate' => '{product_name}|{product_url}',
        ], $product);

        $this->assertSame('https://wa.me/7?text=' . rawurlencode('this product|'), $vm->getWhatsAppUrl());
    }

    public function testDelegatesButtonSettings(): void
    {
        $vm = $this->viewModel([
            'getWhatsAppProductButtonText' => 'Ask',
            'getWhatsAppProductButtonStyle' => 'icon_only',
            'getWhatsAppProductButtonBgColor' => '#fff',
            'getWhatsAppProductButtonTextColor' => '#000',
            'getCustomCssClasses' => 'x',
        ]);

        $this->assertSame('Ask', $vm->getButtonText());
        $this->assertSame('icon_only', $vm->getButtonStyle());
        $this->assertSame('#fff', $vm->getButtonBgColor());
        $this->assertSame('#000', $vm->getButtonTextColor());
        $this->assertSame('x', $vm->getCustomCssClasses());
    }
}
