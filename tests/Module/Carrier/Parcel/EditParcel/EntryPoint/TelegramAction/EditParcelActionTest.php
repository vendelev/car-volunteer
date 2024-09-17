<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Parcel\EditParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionRoute;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\EntryPoint\TelegramAction\EditParcelAction;
use CarVolunteer\Tests\Fake\TestMessageHandler;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use Doctrine\Persistence\ManagerRegistry;
use HardcorePhp\Infrastructure\Uuid\Uuid;

class EditParcelActionTest extends KernelTestCaseDecorator
{
    public function testHandle(): void
    {
        $entity = new Parcel(
            id: Uuid::v7(),
            authorId: '1',
            status: ParcelStatus::Described->value,
            title: '',
            description: '',
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity);
        $manager->flush();

        $telegramMessage = new TelegramMessage(
            '1',
            '',
            new Conversation(new ActionRoute(
                EditParcelAction::getInfo()->route->value,
                ['id' => $entity->id->toString()]
            ))
        );

        $handler = new TestMessageHandler();
        $messageContext = $handler->createMessageContext([SendMessageCommand::class => $handler]);
        $messageContext->setAttribute(new AuthorizeAttribute('11', [UserRole::User]));

        self::getService(EditParcelAction::class)->handle($telegramMessage, $messageContext);

        /** @var list<SendMessageCommand> $messages */
        $messages = $handler->messages;

        self::assertCount(2, $messages);
        self::assertStringContainsString($entity->description, $messages[1]->text);
    }
}
