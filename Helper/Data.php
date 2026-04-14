<?php
/**
 * Copyright © Panth Infotech. All rights reserved.
 */
declare(strict_types=1);

namespace Panth\WhatsApp\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @var string
     */
    private const XML_PATH_WHATSAPP = 'panth_whatsapp/';

    /**
     * Get config value
     *
     * @param string $field
     * @param int|null $storeId
     * @return mixed
     */
    public function getConfigValue(string $field, ?int $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_WHATSAPP . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if Core module is enabled.
     * WhatsApp requires Core module to be enabled.
     *
     * @return bool
     */
    public function isCoreModuleEnabled(): bool
    {
        return true;
    }

    // ===========================================
    // WhatsApp Float Button Settings
    // ===========================================

    /**
     * Check if WhatsApp button is enabled.
     * Also checks if Core module is enabled as a dependency.
     *
     * @return bool
     */
    public function isWhatsAppEnabled(): bool
    {
        if (!$this->isCoreModuleEnabled()) {
            return false;
        }

        return (bool) $this->getConfigValue('general/enabled');
    }

    /**
     * Get WhatsApp phone number
     *
     * @return string
     */
    public function getWhatsAppPhone(): string
    {
        return (string) ($this->getConfigValue('general/phone_number') ?: '+918401270422');
    }

    /**
     * Get WhatsApp default message
     *
     * @return string
     */
    public function getWhatsAppMessage(): string
    {
        return (string) ($this->getConfigValue('general/message')
            ?: __('Hi! I have a question about your products.'));
    }

    /**
     * Get WhatsApp button position
     *
     * @return string
     */
    public function getWhatsAppPosition(): string
    {
        return (string) ($this->getConfigValue('general/position') ?: 'bottom-left');
    }

    /**
     * Get WhatsApp button text
     *
     * @return string
     */
    public function getWhatsAppButtonText(): string
    {
        return (string) ($this->getConfigValue('general/button_text') ?: __('Chat with Us'));
    }

    // ===========================================
    // WhatsApp Product Page Settings
    // ===========================================

    /**
     * Check if WhatsApp product page button is enabled
     *
     * @return bool
     */
    public function isWhatsAppProductEnabled(): bool
    {
        return (bool) $this->getConfigValue('product/enabled');
    }

    /**
     * Get WhatsApp product button text
     *
     * @return string
     */
    public function getWhatsAppProductButtonText(): string
    {
        return (string) ($this->getConfigValue('product/button_text') ?: __('Ask on WhatsApp'));
    }

    /**
     * Get WhatsApp product message template
     *
     * @return string
     */
    public function getWhatsAppProductMessageTemplate(): string
    {
        return (string) ($this->getConfigValue('product/message_template')
            ?: __("Hi! I'm interested in {product_name}. {product_url}"));
    }

    /**
     * Get WhatsApp product button style
     *
     * @return string
     */
    public function getWhatsAppProductButtonStyle(): string
    {
        return (string) ($this->getConfigValue('product/button_style') ?: 'default');
    }

    /**
     * Get WhatsApp product button background color (from CSS variable --whatsapp-button-bg)
     *
     * @return string
     * @deprecated Colors are now managed via CSS variables in theme-config.json
     */
    public function getWhatsAppProductButtonBgColor(): string
    {
        return '';
    }

    /**
     * Get WhatsApp product button text color (from CSS variable --whatsapp-button-text)
     *
     * @return string
     * @deprecated Colors are now managed via CSS variables in theme-config.json
     */
    public function getWhatsAppProductButtonTextColor(): string
    {
        return '';
    }

    // ===========================================
    // WhatsApp Category Page Settings
    // ===========================================

    /**
     * Check if WhatsApp category page banner is enabled
     *
     * @return bool
     */
    public function isWhatsAppCategoryEnabled(): bool
    {
        return (bool) $this->getConfigValue('category/enabled');
    }

    /**
     * Get WhatsApp category button text
     *
     * @return string
     */
    public function getWhatsAppCategoryButtonText(): string
    {
        return (string) ($this->getConfigValue('category/button_text') ?: __('Chat with Us'));
    }

    /**
     * Get WhatsApp category message template
     *
     * @return string
     */
    public function getWhatsAppCategoryMessageTemplate(): string
    {
        return (string) ($this->getConfigValue('category/message_template')
            ?: __("Hi! I need help finding products in your store."));
    }

    // ===========================================
    // Advanced Styling Settings
    // ===========================================

    /**
     * Get custom CSS classes
     *
     * @return string
     */
    public function getCustomCssClasses(): string
    {
        $classes = (string) $this->getConfigValue('advanced/custom_css_classes');
        // Convert line breaks to spaces and clean up
        return trim((string) preg_replace('/\s+/', ' ', str_replace(["\r\n", "\r", "\n"], ' ', $classes)));
    }
}
