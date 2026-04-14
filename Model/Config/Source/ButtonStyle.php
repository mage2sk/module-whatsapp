<?php
/**
 * Copyright © Panth Infotech. All rights reserved.
 */
declare(strict_types=1);

namespace Panth\WhatsApp\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ButtonStyle implements OptionSourceInterface
{
    /**
     * Get button style options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'solid', 'label' => __('Solid (Filled Background)')],
            ['value' => 'outline', 'label' => __('Outline (Border Only)')],
            ['value' => 'icon_only', 'label' => __('Icon Only (Compact Circle)')],
            ['value' => 'text_only', 'label' => __('Text Only (No Background)')],
        ];
    }
}
