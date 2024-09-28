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
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelChangeDescriptionEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\EntryPoint\TelegramAction\EditParcelDescriptionAction;
use CarVolunteer\Tests\Fake\TestMessageHandler;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use Doctrine\Persistence\ManagerRegistry;
use HardcorePhp\Infrastructure\Uuid\Uuid;

class EditParcelDescriptionActionTest extends KernelTestCaseDecorator
{
    public function testHandle(): void
    {
        $entity = new Parcel(
            id: Uuid::v7(),
            authorId: '1',
            status: ParcelStatus::Described,
            title: '',
            description: '',
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity);
        $manager->flush();

        $telegramMessage = new TelegramMessage(
            '1',
            'test',
            new Conversation(new ActionRoute(
                EditParcelDescriptionAction::getInfo()->route->value,
                ['id' => $entity->id->toString()]
            ))
        );

        $handler = new TestMessageHandler();
        $messageContext = $handler->createMessageContext([
            ParcelChangeDescriptionEvent::class => $handler,
            SendMessageCommand::class => $handler,
        ]);
        $messageContext->setAttribute(new AuthorizeAttribute('11', [UserRole::Receiver, UserRole::User]));

        self::getService(EditParcelDescriptionAction::class)->handle($telegramMessage, $messageContext);

        /** @var ParcelChangeDescriptionEvent $message */
        $message = $handler->messages[0];

        self::assertCount(2, $handler->messages);
        self::assertStringContainsString($entity->description, $message->parcel->description);
    }
}
