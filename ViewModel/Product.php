<?php
declare(strict_types=1);

namespace Panth\WhatsApp\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Registry;
use Panth\WhatsApp\Helper\Data as WhatsAppHelper;

class Product implements ArgumentInterface
{
    private $whatsappHelper;

    private $registry;

    public function __construct(
        WhatsAppHelper $whatsappHelper,
        Registry $registry
    ) {
        $this->whatsappHelper = $whatsappHelper;
        $this->registry = $registry;
    }

    public function isEnabled(): bool
    {
        return $this->whatsappHelper->isWhatsAppEnabled()
            && $this->whatsappHelper->isWhatsAppProductEnabled();
    }

    public function getProduct()
    {
        return $this->registry->registry('current_product');
    }

    public function getButtonText(): string
    {
        return $this->whatsappHelper->getWhatsAppProductButtonText();
    }

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
            $whatsappUrl .= '?text=' . rawurlencode($message);
        }

        return $whatsappUrl;
    }

    public function getButtonStyle(): string
    {
        return $this->whatsappHelper->getWhatsAppProductButtonStyle();
    }

    public function getButtonBgColor(): string
    {
        return $this->whatsappHelper->getWhatsAppProductButtonBgColor();
    }

    public function getButtonTextColor(): string
    {
        return $this->whatsappHelper->getWhatsAppProductButtonTextColor();
    }

    public function getCustomCssClasses(): string
    {
        return $this->whatsappHelper->getCustomCssClasses();
    }
}
