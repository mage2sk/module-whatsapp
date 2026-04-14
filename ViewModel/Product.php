<?php
/**
 * Copyright © Panth Infotech. All rights reserved.
 *
 * WhatsApp Product ViewModel
 */
declare(strict_types=1);

namespace Panth\WhatsApp\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Registry;
use Panth\WhatsApp\Helper\Data as WhatsAppHelper;

class Product implements ArgumentInterface
{
    /**
     * @var WhatsAppHelper
     */
    private $whatsappHelper;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param WhatsAppHelper $whatsappHelper
     * @param Registry $registry
     */
    public function __construct(
        WhatsAppHelper $whatsappHelper,
        Registry $registry
    ) {
        $this->whatsappHelper = $whatsappHelper;
        $this->registry = $registry;
    }

    /**
     * Check if WhatsApp is enabled for product pages
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->whatsappHelper->isWhatsAppEnabled()
            && $this->whatsappHelper->isWhatsAppProductEnabled();
    }

    /**
     * Get current product
     *
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * Get WhatsApp button text
     *
     * @return string
     */
    public function getButtonText(): string
    {
        return $this->whatsappHelper->getWhatsAppProductButtonText();
    }

    /**
     * Get WhatsApp URL for current product
     *
     * @return string
     */
    public function getWhatsAppUrl(): string
    {
        $product = $this->getProduct();
        if (!$product || !$product->getId()) {
            return '';
        }

        $phone = $this->whatsappHelper->getWhatsAppPhone();
        $messageTemplate = $this->whatsappHelper->getWhatsAppProductMessageTemplate();

        try {
            $productName = $product->getName() ?: 'this product';
            $productUrl = $product->getProductUrl() ?: '';
        } catch (\Exception $e) {
            $productName = 'this product';
            $productUrl = '';
        }

        $message = str_replace(
            ['{product_name}', '{product_url}'],
            [$productName, $productUrl],
            $messageTemplate ?: "Hi! I'm interested in {product_name}"
        );

        $whatsappUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone);
        if ($message) {
            $whatsappUrl .= '?text=' . urlencode($message);
        }

        return $whatsappUrl;
    }

    /**
     * Get button style configuration
     *
     * @return string
     */
    public function getButtonStyle(): string
    {
        return $this->whatsappHelper->getWhatsAppProductButtonStyle();
    }

    /**
     * Get button background color
     *
     * @return string
     */
    public function getButtonBgColor(): string
    {
        return $this->whatsappHelper->getWhatsAppProductButtonBgColor();
    }

    /**
     * Get button text color
     *
     * @return string
     */
    public function getButtonTextColor(): string
    {
        return $this->whatsappHelper->getWhatsAppProductButtonTextColor();
    }

    /**
     * Get custom CSS classes from admin config
     *
     * @return string
     */
    public function getCustomCssClasses(): string
    {
        return $this->whatsappHelper->getCustomCssClasses();
    }
}
