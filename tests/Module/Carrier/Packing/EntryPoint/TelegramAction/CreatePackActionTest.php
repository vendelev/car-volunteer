<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Packing\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionRoute;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Packing\Domain\UserClickEvent;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\TelegramAction\CreatePackAction;
use CarVolunteer\Tests\Fake\TestMessageHandler;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use HardcorePhp\Infrastructure\Uuid\Uuid;

class CreatePackActionTest extends KernelTestCaseDecorator
{
    public function testHandle(): void
    {
        $telegramMessage = new TelegramMessage(
            '1',
            '',
            new Conversation(new ActionRoute(
                CreatePackAction::getInfo()->route->value,
                ['parcelId' => Uuid::v7()->toString(), 'want' => UserClickEvent::LoadPhoto->value]
            ))
        );

        $handler = new TestMessageHandler();
        $messageContext = $handler->createMessageContext([SendMessageCommand::class => $handler]);
        $messageContext->setAttribute(new AuthorizeAttribute('11', [UserRole::Manager]));

        self::getService(CreatePackAction::class)->handle($telegramMessage, $messageContext);

        /** @var list<SendMessageCommand> $messages */
        $messages = $handler->messages;

        self::assertCount(1, $messages);
        self::assertStringContainsString('Отправьте несколько фотографий в одном сообщении', $messages[0]->text);
    }
}
