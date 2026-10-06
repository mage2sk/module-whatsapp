<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Test\Unit\Etc;

use Panth\WhatsApp\Model\Config\Source\ButtonStyle;
use Panth\WhatsApp\Model\Config\Source\Position;
use PHPUnit\Framework\TestCase;

class ModuleConfigTest extends TestCase
{
    private function load(string $relative): \SimpleXMLElement
    {
        $path = dirname(__DIR__, 3) . '/' . $relative;
        $this->assertTrue(is_file($path), $relative . ' exists');
        $previous = libxml_use_internal_errors(true);
        $xml = simplexml_load_string((string) file_get_contents($path));
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        $this->assertInstanceOf(\SimpleXMLElement::class, $xml, $relative . ' is valid XML');

        return $xml;
    }

    /**
     * @param array<int, array{value: string, label: mixed}> $options
     * @return string[]
     */
    private function values(array $options): array
    {
        return array_map(static fn(array $option): string => (string) $option['value'], $options);
    }

    public function testDefaultsAreSafeAndMatchSourceModels(): void
    {
        $defaults = $this->load('etc/config.xml')->default->panth_whatsapp;

        $this->assertSame('0', (string) $defaults->general->enabled);
        $this->assertSame('0', (string) $defaults->product->enabled);
        $this->assertSame('0', (string) $defaults->category->enabled);
        $this->assertSame('', trim((string) $defaults->general->phone_number));
        $this->assertContains((string) $defaults->general->position, $this->values((new Position())->toOptionArray()));
        $this->assertContains(
            (string) $defaults->product->button_style,
            $this->values((new ButtonStyle())->toOptionArray())
        );
        $this->assertStringContainsString('{product_name}', (string) $defaults->product->message_template);
    }

    public function testFloatButtonRendersAtEndOfBodyBehindEnableFlag(): void
    {
        $layout = $this->load('view/frontend/layout/default.xml');
        $blocks = $layout->xpath('//referenceContainer[@name="before.body.end"]/block[@name="whatsapp.float"]');

        $this->assertCount(1, $blocks);
        $this->assertSame('panth_whatsapp/general/enabled', (string) $blocks[0]['ifconfig']);
        $this->assertSame([], $layout->xpath('//referenceContainer[@name="after.body.start"]'));
    }

    public function testProductButtonSitsOutsideBuyBox(): void
    {
        $product = $this->load('view/frontend/layout/catalog_product_view.xml');
        $hyva = $this->load('view/frontend/layout/hyva_catalog_product_view.xml');

        $blocks = $product->xpath('//referenceContainer[@name="content"]/block[@name="whatsapp.product.button"]');
        $this->assertCount(1, $blocks);
        $this->assertSame('product.info.main', (string) $blocks[0]['after']);
        $this->assertSame([], $product->xpath('//block[@name="whatsapp.product.button.hyva"]'));
        $this->assertSame([], $product->xpath('//referenceContainer[@name="product.info.main"]'));
        $this->assertSame([], $product->xpath('//referenceContainer[@name="product.info.additional.actions"]'));

        $moves = $hyva->xpath('//move[@element="whatsapp.product.button"]');
        $this->assertCount(1, $moves);
        $this->assertSame('product.info.additional', (string) $moves[0]['destination']);
        $this->assertSame([], $hyva->xpath('//referenceBlock[@remove="true"]'));
    }

    public function testProductButtonTemplateRendersLabelledSecondaryButton(): void
    {
        $path = dirname(__DIR__, 3) . '/view/frontend/templates/product/button.phtml';
        $this->assertTrue(is_file($path));
        $template = (string) file_get_contents($path);

        $this->assertStringContainsString('<div class="wa-product-cta">', $template);
        $this->assertStringContainsString('.wa-product-cta .wa-product-btn:not(.wa-style-icon)', $template);
        $this->assertStringContainsString(
            '<span class="wa-btn-label"><?= $escaper->escapeHtml($buttonText) ?></span>',
            $template
        );
        $this->assertStringContainsString('aria-hidden="true"', $template);
        $this->assertStringNotContainsString('border-radius: 10px', $template);
    }

    public function testCategoryBannerPlacedBelowProductList(): void
    {
        $blocks = $this->load('view/frontend/layout/catalog_category_view.xml')
            ->xpath('//referenceContainer[@name="content"]/block[@name="whatsapp.category.banner"]');

        $this->assertCount(1, $blocks);
        $this->assertSame('-', (string) $blocks[0]['after']);
        $this->assertNull($blocks[0]['before']);
    }

    public function testFeatureFlagsExplainSharedFloatSettings(): void
    {
        $section = $this->load('etc/adminhtml/system.xml')->system->section;

        $this->assertSame('Panth_WhatsApp::config', (string) $section->resource);
        foreach (['product', 'category'] as $group) {
            $field = $section->xpath('group[@id="' . $group . '"]/field[@id="enabled"]');
            $this->assertCount(1, $field);
            $this->assertStringContainsString('Enable WhatsApp Float Button', (string) $field[0]->comment);
        }
        $phone = $section->xpath('group[@id="general"]/field[@id="phone_number"]');
        $this->assertSame('required-entry', (string) $phone[0]->validate);
    }
}
