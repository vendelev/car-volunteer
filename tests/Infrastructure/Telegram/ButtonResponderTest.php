<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Infrastructure\Telegram;

use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Infrastructure\Telegram\RouteParam;
use CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction\StartAction;
use PHPUnit\Framework\TestCase;

class ButtonResponderTest extends TestCase
{
    public function testDefaultTitle(): void
    {
        $responder = new ButtonResponder();
        self::assertEquals(
            ['text' => 'title', 'callback_data' => ActionRouteMap::RootStart->value],
            $responder->generate($this->getInfo())
        );
    }

    public function testCustomTitle(): void
    {
        $responder = new ButtonResponder();
        self::assertEquals(
            ['text' => 'test', 'callback_data' => ActionRouteMap::RootStart->value],
            $responder->generate($this->getInfo(), null, 'test')
        );
    }

    public function testParam(): void
    {
        $responder = new ButtonResponder();
        self::assertEquals(
            ['text' => 'title', 'callback_data' => ActionRouteMap::RootStart->value . '?id=test'],
            $responder->generate($this->getInfo(), new RouteParam('id', 'test'))
        );
    }

    private function getInfo(): ActionInfo
    {
        return new ActionInfo(StartAction::class, 'title', ActionRouteMap::RootStart);
    }
}
