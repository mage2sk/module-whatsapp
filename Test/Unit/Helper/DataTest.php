<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Panth\WhatsApp\Helper\Data;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    /**
     * @var array<int, array<int, mixed>>
     */
    private array $calls = [];

    /**
     * @param array<string, mixed> $values
     */
    private function helper(array $values): Data
    {
        $this->calls = [];
        $scopeConfig = $this->createStub(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturnCallback(
            function (string $path, string $scope, $storeId = null) use ($values) {
                $this->calls[] = [$path, $scope, $storeId];
                return $values[$path] ?? null;
            }
        );
        $context = $this->createStub(Context::class);
        $context->method('getScopeConfig')->willReturn($scopeConfig);

        return new Data($context);
    }

    public function testGetConfigValuePrefixesPathAndPassesStoreScope(): void
    {
        $helper = $this->helper(['panth_whatsapp/general/phone_number' => '123']);

        $this->assertSame('123', $helper->getConfigValue('general/phone_number', 7));
        $this->assertSame(
            [['panth_whatsapp/general/phone_number', ScopeInterface::SCOPE_STORE, 7]],
            $this->calls
        );
    }

    public function testCoreModuleIsAlwaysEnabled(): void
    {
        $this->assertTrue($this->helper([])->isCoreModuleEnabled());
    }

    public function testWhatsAppEnabledRequiresFlagAndPhoneDigits(): void
    {
        $this->assertTrue($this->helper([
            'panth_whatsapp/general/enabled' => '1',
            'panth_whatsapp/general/phone_number' => '+44 (20) 7946-0000',
        ])->isWhatsAppEnabled());
    }

    public function testWhatsAppDisabledWhenFlagOff(): void
    {
        $this->assertFalse($this->helper([
            'panth_whatsapp/general/enabled' => '0',
            'panth_whatsapp/general/phone_number' => '123456',
        ])->isWhatsAppEnabled());
    }

    public function testWhatsAppDisabledWhenPhoneHasNoDigits(): void
    {
        $this->assertFalse($this->helper([
            'panth_whatsapp/general/enabled' => '1',
            'panth_whatsapp/general/phone_number' => '+ ( ) -',
        ])->isWhatsAppEnabled());
        $this->assertFalse($this->helper(['panth_whatsapp/general/enabled' => '1'])->isWhatsAppEnabled());
    }

    public function testPhoneIsCastToString(): void
    {
        $this->assertSame('', $this->helper([])->getWhatsAppPhone());
        $this->assertSame(
            '5551234',
            $this->helper(['panth_whatsapp/general/phone_number' => 5551234])->getWhatsAppPhone()
        );
    }

    public function testStringSettingsReturnConfiguredValues(): void
    {
        $helper = $this->helper([
            'panth_whatsapp/general/message' => 'Hello',
            'panth_whatsapp/general/position' => 'top-right',
            'panth_whatsapp/general/button_text' => 'Talk',
            'panth_whatsapp/product/button_text' => 'Ask',
            'panth_whatsapp/product/message_template' => 'About {product_name}',
            'panth_whatsapp/product/button_style' => 'outline',
            'panth_whatsapp/category/button_text' => 'Help',
            'panth_whatsapp/category/message_template' => 'Category help',
        ]);

        $this->assertSame('Hello', $helper->getWhatsAppMessage());
        $this->assertSame('top-right', $helper->getWhatsAppPosition());
        $this->assertSame('Talk', $helper->getWhatsAppButtonText());
        $this->assertSame('Ask', $helper->getWhatsAppProductButtonText());
        $this->assertSame('About {product_name}', $helper->getWhatsAppProductMessageTemplate());
        $this->assertSame('outline', $helper->getWhatsAppProductButtonStyle());
        $this->assertSame('Help', $helper->getWhatsAppCategoryButtonText());
        $this->assertSame('Category help', $helper->getWhatsAppCategoryMessageTemplate());
    }

    public function testStringSettingsFallBackToDefaultsWhenEmpty(): void
    {
        $helper = $this->helper([
            'panth_whatsapp/general/message' => '',
            'panth_whatsapp/general/position' => '',
        ]);

        $this->assertSame('Hi! I have a question about your products.', $helper->getWhatsAppMessage());
        $this->assertSame('bottom-left', $helper->getWhatsAppPosition());
        $this->assertSame('Chat with Us', $helper->getWhatsAppButtonText());
        $this->assertSame('Ask on WhatsApp', $helper->getWhatsAppProductButtonText());
        $this->assertSame(
            "Hi! I'm interested in {product_name}. {product_url}",
            $helper->getWhatsAppProductMessageTemplate()
        );
        $this->assertSame('solid', $helper->getWhatsAppProductButtonStyle());
        $this->assertSame('Chat with Us', $helper->getWhatsAppCategoryButtonText());
        $this->assertSame(
            'Hi! I need help finding products in your store.',
            $helper->getWhatsAppCategoryMessageTemplate()
        );
    }

    public function testProductAndCategoryFlags(): void
    {
        $on = $this->helper([
            'panth_whatsapp/product/enabled' => '1',
            'panth_whatsapp/category/enabled' => '1',
        ]);
        $this->assertTrue($on->isWhatsAppProductEnabled());
        $this->assertTrue($on->isWhatsAppCategoryEnabled());

        $off = $this->helper([]);
        $this->assertFalse($off->isWhatsAppProductEnabled());
        $this->assertFalse($off->isWhatsAppCategoryEnabled());
    }

    public function testButtonColorsAreAlwaysEmpty(): void
    {
        $helper = $this->helper([]);

        $this->assertSame('', $helper->getWhatsAppProductButtonBgColor());
        $this->assertSame('', $helper->getWhatsAppProductButtonTextColor());
    }

    public function testCustomCssClassesCollapseWhitespaceAndNewlines(): void
    {
        $helper = $this->helper([
            'panth_whatsapp/advanced/custom_css_classes' => "  foo\r\nbar\rbaz\n\tqux   quux  ",
        ]);

        $this->assertSame('foo bar baz qux quux', $helper->getCustomCssClasses());
    }

    public function testCustomCssClassesEmptyWhenUnset(): void
    {
        $this->assertSame('', $this->helper([])->getCustomCssClasses());
    }
}
