<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Test\Unit\Model\Config\Source;

use Panth\WhatsApp\Model\Config\Source\ButtonStyle;
use Panth\WhatsApp\Model\Config\Source\Position;
use PHPUnit\Framework\TestCase;

class SourceModelsTest extends TestCase
{
    public function testButtonStyleOptionValues(): void
    {
        $options = (new ButtonStyle())->toOptionArray();

        $this->assertSame(
            ['solid', 'outline', 'icon_only', 'text_only', 'default'],
            array_column($options, 'value')
        );
        foreach ($options as $option) {
            $this->assertNotSame('', (string) $option['label']);
        }
    }

    public function testPositionOptionValues(): void
    {
        $values = array_column((new Position())->toOptionArray(), 'value');

        $this->assertSame(['bottom-right', 'bottom-left', 'top-right', 'top-left'], $values);
    }
}
