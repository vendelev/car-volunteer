<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Parcel\CreateParcel\EntryPoint\BusHandler;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\EntryPoint\BusHandler\NotifyNewParcelHandler;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelCreatedEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelPlayLoad;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Tests\Fake\TestMessageHandler;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use HardcorePhp\Infrastructure\Uuid\Uuid;

class NotifyNewParcelHandlerTest extends KernelTestCaseDecorator
{
    public function testHandle(): void
    {
        $handler = new TestMessageHandler();
        $messageContext = $handler->createMessageContext([SendMessageCommand::class => $handler]);
        $messageContext->setAttribute(new AuthorizeAttribute('11', [UserRole::User]));
        $message = new ParcelCreatedEvent(
            '11',
            new ParcelPlayLoad(
                Uuid::v7(),
                ParcelStatus::Described,
                'Test1'
            )
        );

        self::getService(NotifyNewParcelHandler::class)->handle($message, $messageContext);

        /** @var list<SendMessageCommand> $messages */
        $messages = $handler->messages;

        self::assertCount(2, $messages);

        self::assertEquals('10', $messages[0]->chatId);
        self::assertStringContainsString('Test1', $messages[0]->text);
        self::assertNotNull($messages[0]->replyMarkup);

        self::assertEquals('12', $messages[1]->chatId);
        self::assertStringContainsString('Test1', $messages[1]->text);
        self::assertNotNull($messages[1]->replyMarkup);
    }
}
