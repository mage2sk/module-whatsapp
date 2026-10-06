<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Test\Unit\ViewModel;

use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Panth\WhatsApp\Helper\Data;
use Panth\WhatsApp\ViewModel\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /**
     * @param array<string, mixed> $returns
     * @param mixed $category
     */
    private function viewModel(array $returns, $category = null): Category
    {
        $helper = $this->createStub(Data::class);
        foreach ($returns as $method => $value) {
            $helper->method($method)->willReturn($value);
        }
        $registry = $this->createStub(Registry::class);
        $registry->method('registry')->willReturnCallback(
            static fn(string $key) => $key === 'current_category' ? $category : null
        );

        return new Category($helper, $registry);
    }

    public function testEnabledRequiresGlobalAndCategoryFlags(): void
    {
        $this->assertTrue($this->viewModel([
            'isWhatsAppEnabled' => true,
            'isWhatsAppCategoryEnabled' => true,
        ])->isEnabled());
        $this->assertFalse($this->viewModel([
            'isWhatsAppEnabled' => true,
            'isWhatsAppCategoryEnabled' => false,
        ])->isEnabled());
        $this->assertFalse($this->viewModel([
            'isWhatsAppEnabled' => false,
            'isWhatsAppCategoryEnabled' => true,
        ])->isEnabled());
    }

    public function testGetCategoryReadsRegistry(): void
    {
        $category = new DataObject(['name' => 'Shoes']);

        $this->assertSame($category, $this->viewModel([], $category)->getCategory());
        $this->assertNull($this->viewModel([])->getCategory());
    }

    public function testUrlAppendsCurrentCategoryName(): void
    {
        $vm = $this->viewModel([
            'getWhatsAppPhone' => '+31 6 1234',
            'getWhatsAppCategoryMessageTemplate' => 'Need help.',
        ], new DataObject(['name' => 'Shoes & Boots']));

        $this->assertSame(
            'https://wa.me/3161234?text=' . rawurlencode('Need help. (Current category: Shoes & Boots)'),
            $vm->getWhatsAppUrl()
        );
    }

    public function testUrlWithoutCategoryUsesTemplateOnly(): void
    {
        $vm = $this->viewModel([
            'getWhatsAppPhone' => '99',
            'getWhatsAppCategoryMessageTemplate' => 'Need help.',
        ]);

        $this->assertSame('https://wa.me/99?text=Need%20help.', $vm->getWhatsAppUrl());
    }

    public function testDelegatesButtonTextAndCss(): void
    {
        $vm = $this->viewModel(['getWhatsAppCategoryButtonText' => 'Help', 'getCustomCssClasses' => 'c']);

        $this->assertSame('Help', $vm->getButtonText());
        $this->assertSame('c', $vm->getCustomCssClasses());
    }
}
