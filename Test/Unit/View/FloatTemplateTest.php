<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Test\Unit\View;

use PHPUnit\Framework\TestCase;

class FloatTemplateTest extends TestCase
{
    private string $template = '';

    private string $moduleDir = '';

    protected function setUp(): void
    {
        $this->moduleDir = dirname(__DIR__, 3);
        $path = $this->moduleDir . '/view/frontend/templates/whatsapp-float.phtml';
        $this->assertTrue(is_file($path));
        $this->template = (string) file_get_contents($path);
    }

    public function testBackToTopOffsetLeavesConfigurableGapAboveIt(): void
    {
        $this->assertStringContainsString(
            '--whatsapp-float-bottom-with-btt: calc(var(--panth-float-edge, 24px)'
            . ' + var(--whatsapp-float-size, 56px) + var(--whatsapp-float-gap, 16px));',
            $this->template
        );
    }

    public function testBottomOffsetIsSafeAreaAware(): void
    {
        $this->assertStringContainsString('env(safe-area-inset-bottom, 0px)', $this->template);
        $this->assertStringContainsString('var(--panth-float-slot-btt-', $this->template);
    }

    public function testButtonIsAtMostFiftySixPixels(): void
    {
        $this->assertStringContainsString('width: var(--whatsapp-float-size, 56px);', $this->template);
        $this->assertStringContainsString('height: var(--whatsapp-float-size, 56px);', $this->template);
        $this->assertStringContainsString('width: var(--whatsapp-float-size-mobile, 56px) !important;', $this->template);
    }

    public function testHiddenOnCheckout(): void
    {
        $this->assertStringContainsString('body.checkout-index-index #<?= $escaper->escapeHtmlAttr($uniqueId) ?> {', $this->template);
    }

    public function testThemeConfigDefinesSixteenPixelGap(): void
    {
        $path = $this->moduleDir . '/etc/theme-config.json';
        $this->assertTrue(is_file($path));
        $config = json_decode((string) file_get_contents($path), true);
        $this->assertIsArray($config);
        $this->assertSame('16px', $config['whatsapp-float-gap'] ?? null);
        $this->assertSame('56px', $config['whatsapp-float-size'] ?? null);
        $this->assertArrayNotHasKey('whatsapp-float-bottom-with-btt', $config);
    }
}
