<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Packing\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionRoute;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\TelegramAction\ViewPackingPhotoAction;
use CarVolunteer\Tests\Fake\TestMessageHandler;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use HardcorePhp\Infrastructure\Uuid\Uuid;

class ViewPackingPhotoActionTest extends KernelTestCaseDecorator
{
    public function testHandle(): void
    {
        $telegramMessage = new TelegramMessage(
            '1',
            '',
            new Conversation(new ActionRoute(
                ViewPackingPhotoAction::getInfo()->route->value,
                ['id' => Uuid::v7()->toString()]
            ))
        );

        $handler = new TestMessageHandler();
        $messageContext = $handler->createMessageContext([SendMessageCommand::class => $handler]);
        $messageContext->setAttribute(new AuthorizeAttribute('11', [UserRole::User]));

        self::getService(ViewPackingPhotoAction::class)->handle($telegramMessage, $messageContext);
        self::assertCount(1, $handler->messages);
    }
}
