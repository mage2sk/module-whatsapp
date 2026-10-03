<?php
declare(strict_types=1);

namespace Panth\WhatsApp\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Registry;
use Panth\WhatsApp\Helper\Data as WhatsAppHelper;

class Category implements ArgumentInterface
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
            && $this->whatsappHelper->isWhatsAppCategoryEnabled();
    }

    public function getCategory()
    {
        return $this->registry->registry('current_category');
    }

    public function getWhatsAppUrl(): string
    {
        $category = $this->getCategory();
        $phone = $this->whatsappHelper->getWhatsAppPhone();
        $messageTemplate = $this->whatsappHelper->getWhatsAppCategoryMessageTemplate();

        $message = $messageTemplate;
        if ($category) {
            $message .= ' ' . __('(Current category: %1)', $category->getName());
        }

        $whatsappUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone);
        $whatsappUrl .= '?text=' . rawurlencode($message);

        return $whatsappUrl;
    }

    public function getButtonText(): string
    {
        return $this->whatsappHelper->getWhatsAppCategoryButtonText();
    }

    public function getCustomCssClasses(): string
    {
        return $this->whatsappHelper->getCustomCssClasses();
    }
}
