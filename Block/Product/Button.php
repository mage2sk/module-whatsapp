<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Block\Product;

use Magento\Framework\View\Element\Template;
use Panth\WhatsApp\ViewModel\Product as ProductViewModel;

class Button extends Template
{
    public function getViewModel(): ?ProductViewModel
    {
        return $this->getData('view_model');
    }
}
