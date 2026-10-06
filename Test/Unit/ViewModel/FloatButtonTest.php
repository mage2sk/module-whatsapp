<?php
declare(strict_types=1);

namespace Panth\WhatsApp\Test\Unit\ViewModel;

use Panth\WhatsApp\Helper\Data;
use Panth\WhatsApp\ViewModel\FloatButton;
use PHPUnit\Framework\TestCase;

class FloatButtonTest extends TestCase
{
    /**
     * @param array<string, mixed> $returns
     */
    private function viewModel(array $returns): FloatButton
    {
        $helper = $this->createStub(Data::class);
        foreach ($returns as $method => $value) {
            $helper->method($method)->willReturn($value);
        }

        return new FloatButton($helper);
    }

    public function testDelegatesSimpleValuesToHelper(): void
    {
        $vm = $this->viewModel([
            'isWhatsAppEnabled' => true,
            'getWhatsAppPhone' => '+1 555',
            'getWhatsAppMessage' => 'Hi',
            'getWhatsAppPosition' => 'top-left',
            'getWhatsAppButtonText' => 'Chat',
            'getCustomCssClasses' => 'a b',
        ]);

        $this->assertTrue($vm->isEnabled());
        $this->assertSame('+1 555', $vm->getPhone());
        $this->assertSame('Hi', $vm->getMessage());
        $this->assertSame('top-left', $vm->getPosition());
        $this->assertSame('Chat', $vm->getButtonText());
        $this->assertSame('a b', $vm->getCustomCssClasses());
    }

    public function testPositionFallsBackToBottomLeft(): void
    {
        $this->assertSame('bottom-left', $this->viewModel(['getWhatsAppPosition' => ''])->getPosition());
    }

    public function testUrlStripsNonDigitsAndEncodesMessage(): void
    {
        $vm = $this->viewModel([
            'getWhatsAppPhone' => '+44 (20) 7946-0000',
            'getWhatsAppMessage' => 'Hi & hello?',
        ]);

        $this->assertSame('https://wa.me/442079460000?text=Hi%20%26%20hello%3F', $vm->getWhatsAppUrl());
    }

    public function testUrlOmitsTextWhenMessageEmpty(): void
    {
        $vm = $this->viewModel(['getWhatsAppPhone' => '123', 'getWhatsAppMessage' => '']);

        $this->assertSame('https://wa.me/123', $vm->getWhatsAppUrl());
    }
}
