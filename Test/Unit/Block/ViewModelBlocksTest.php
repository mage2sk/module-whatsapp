<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Test\Unit\Block;

use Panth\WhatsApp\Block\Category\Banner;
use Panth\WhatsApp\Block\Product\Button;
use Panth\WhatsApp\ViewModel\Category;
use Panth\WhatsApp\ViewModel\Product;
use PHPUnit\Framework\TestCase;

class ViewModelBlocksTest extends TestCase
{
    public function testBannerReturnsAssignedViewModel(): void
    {
        $block = (new \ReflectionClass(Banner::class))->newInstanceWithoutConstructor();
        $this->assertNull($block->getViewModel());

        $viewModel = $this->createStub(Category::class);
        $block->setData('view_model', $viewModel);
        $this->assertSame($viewModel, $block->getViewModel());
    }

    public function testButtonReturnsAssignedViewModel(): void
    {
        $block = (new \ReflectionClass(Button::class))->newInstanceWithoutConstructor();
        $this->assertNull($block->getViewModel());

        $viewModel = $this->createStub(Product::class);
        $block->setData('view_model', $viewModel);
        $this->assertSame($viewModel, $block->getViewModel());
    }
}
