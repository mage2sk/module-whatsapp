<?php
/**
 * Copyright © Panth Infotech. All rights reserved.
 *
 * WhatsApp Category ViewModel
 */
declare(strict_types=1);

namespace Panth\WhatsApp\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Registry;
use Panth\WhatsApp\Helper\Data as WhatsAppHelper;

class Category implements ArgumentInterface
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
     * Check if WhatsApp is enabled for category pages
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->whatsappHelper->isWhatsAppEnabled()
            && $this->whatsappHelper->isWhatsAppCategoryEnabled();
    }

    /**
     * Get current category
     *
     * @return \Magento\Catalog\Model\Category|null
     */
    public function getCategory()
    {
        return $this->registry->registry('current_category');
    }

    /**
     * Get WhatsApp URL for category inquiry
     *
     * @return string
     */
    public function getWhatsAppUrl(): string
    {
        $category = $this->getCategory();
        $phone = $this->whatsappHelper->getWhatsAppPhone();
        $messageTemplate = $this->whatsappHelper->getWhatsAppCategoryMessageTemplate();

        $message = $messageTemplate;
        if ($category) {
            $message .= ' (Current category: ' . $category->getName() . ')';
        }

        $whatsappUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone);
        $whatsappUrl .= '?text=' . urlencode($message);

        return $whatsappUrl;
    }

    /**
     * Get button text
     *
     * @return string
     */
    public function getButtonText(): string
    {
        return $this->whatsappHelper->getWhatsAppCategoryButtonText();
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
