<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    private const XML_PATH_WHATSAPP = 'panth_whatsapp/';

    public function getConfigValue(string $field, ?int $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_WHATSAPP . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isCoreModuleEnabled(): bool
    {
        return true;
    }

    public function isWhatsAppEnabled(): bool
    {
        if (!$this->isCoreModuleEnabled()) {
            return false;
        }

        return (bool) $this->getConfigValue('general/enabled')
            && preg_replace('/[^0-9]/', '', $this->getWhatsAppPhone()) !== '';
    }

    public function getWhatsAppPhone(): string
    {
        return (string) $this->getConfigValue('general/phone_number');
    }

    public function getWhatsAppMessage(): string
    {
        return (string) ($this->getConfigValue('general/message')
            ?: __('Hi! I have a question about your products.'));
    }

    public function getWhatsAppPosition(): string
    {
        return (string) ($this->getConfigValue('general/position') ?: 'bottom-left');
    }

    public function getWhatsAppButtonText(): string
    {
        return (string) ($this->getConfigValue('general/button_text') ?: __('Chat with Us'));
    }

    public function isWhatsAppProductEnabled(): bool
    {
        return (bool) $this->getConfigValue('product/enabled');
    }

    public function getWhatsAppProductButtonText(): string
    {
        return (string) ($this->getConfigValue('product/button_text') ?: __('Ask on WhatsApp'));
    }

    public function getWhatsAppProductMessageTemplate(): string
    {
        return (string) ($this->getConfigValue('product/message_template')
            ?: __("Hi! I'm interested in {product_name}. {product_url}"));
    }

    public function getWhatsAppProductButtonStyle(): string
    {
        return (string) ($this->getConfigValue('product/button_style') ?: 'solid');
    }

    public function getWhatsAppProductButtonBgColor(): string
    {
        return '';
    }

    public function getWhatsAppProductButtonTextColor(): string
    {
        return '';
    }

    public function isWhatsAppCategoryEnabled(): bool
    {
        return (bool) $this->getConfigValue('category/enabled');
    }

    public function getWhatsAppCategoryButtonText(): string
    {
        return (string) ($this->getConfigValue('category/button_text') ?: __('Chat with Us'));
    }

    public function getWhatsAppCategoryMessageTemplate(): string
    {
        return (string) ($this->getConfigValue('category/message_template')
            ?: __("Hi! I need help finding products in your store."));
    }

    public function getCustomCssClasses(): string
    {
        $classes = (string) $this->getConfigValue('advanced/custom_css_classes');

        return trim((string) preg_replace('/\s+/', ' ', str_replace(["\r\n", "\r", "\n"], ' ', $classes)));
    }
}
