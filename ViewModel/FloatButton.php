<?php
declare(strict_types=1);

namespace Panth\WhatsApp\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Panth\WhatsApp\Helper\Data as WhatsAppHelper;

class FloatButton implements ArgumentInterface
{
    private $whatsappHelper;

    public function __construct(
        WhatsAppHelper $whatsappHelper
    ) {
        $this->whatsappHelper = $whatsappHelper;
    }

    public function isEnabled(): bool
    {
        return $this->whatsappHelper->isWhatsAppEnabled();
    }

    public function getPhone(): string
    {
        return $this->whatsappHelper->getWhatsAppPhone();
    }

    public function getMessage(): string
    {
        return $this->whatsappHelper->getWhatsAppMessage();
    }

    public function getPosition(): string
    {
        return $this->whatsappHelper->getWhatsAppPosition() ?: 'bottom-left';
    }

    public function getButtonText(): string
    {
        return $this->whatsappHelper->getWhatsAppButtonText();
    }

    public function getCustomCssClasses(): string
    {
        return $this->whatsappHelper->getCustomCssClasses();
    }

    public function getWhatsAppUrl(): string
    {
        $phone = $this->getPhone();
        $message = $this->getMessage();

        $whatsappUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone);
        if ($message) {
            $whatsappUrl .= '?text=' . rawurlencode($message);
        }

        return $whatsappUrl;
    }
}
