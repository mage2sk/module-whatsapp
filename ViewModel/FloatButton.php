<?php
/**
 * Copyright © Panth Infotech. All rights reserved.
 *
 * WhatsApp Float Button ViewModel
 */
declare(strict_types=1);

namespace Panth\WhatsApp\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Panth\WhatsApp\Helper\Data as WhatsAppHelper;

class FloatButton implements ArgumentInterface
{
    /**
     * @var WhatsAppHelper
     */
    private $whatsappHelper;

    /**
     * @param WhatsAppHelper $whatsappHelper
     */
    public function __construct(
        WhatsAppHelper $whatsappHelper
    ) {
        $this->whatsappHelper = $whatsappHelper;
    }

    /**
     * Check if WhatsApp float button is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->whatsappHelper->isWhatsAppEnabled();
    }

    /**
     * Get WhatsApp phone number
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->whatsappHelper->getWhatsAppPhone();
    }

    /**
     * Get default message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->whatsappHelper->getWhatsAppMessage();
    }

    /**
     * Get button position
     *
     * @return string
     */
    public function getPosition(): string
    {
        return $this->whatsappHelper->getWhatsAppPosition() ?: 'bottom-left';
    }

    /**
     * Get button text
     *
     * @return string
     */
    public function getButtonText(): string
    {
        return $this->whatsappHelper->getWhatsAppButtonText();
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

    /**
     * Get WhatsApp URL
     *
     * @return string
     */
    public function getWhatsAppUrl(): string
    {
        $phone = $this->getPhone();
        $message = $this->getMessage();

        $whatsappUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone);
        if ($message) {
            $whatsappUrl .= '?text=' . urlencode($message);
        }

        return $whatsappUrl;
    }
}
