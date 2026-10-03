<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Block\Category;

use Magento\Framework\View\Element\Template;
use Panth\WhatsApp\ViewModel\Category as CategoryViewModel;

class Banner extends Template
{
    public function getViewModel(): ?CategoryViewModel
    {
        return $this->getData('view_model');
    }
}
