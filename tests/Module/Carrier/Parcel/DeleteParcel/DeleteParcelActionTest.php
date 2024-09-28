<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Parcel\DeleteParcel;

use CarVolunteer\Domain\ActionRoute;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Parcel\DeleteParcel\DeleteParcelAction;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelDeletedEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Tests\Fake\TestMessageHandler;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use HardcorePhp\Infrastructure\Uuid\Uuid;

class DeleteParcelActionTest extends KernelTestCaseDecorator
{
    public function testHandle(): void
    {
        $entity = new Parcel(
            id: Uuid::v7(),
            authorId: '2',
            status: ParcelStatus::Packed,
            title: '',
            description: '',
        );

        /** @var EntityManagerInterface&ManagerRegistry $manager */
        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->getConnection()->beginTransaction();

        $manager->persist($entity);
        $manager->flush();

        $telegramMessage = new TelegramMessage(
            '1',
            '',
            new Conversation(
                new ActionRoute(DeleteParcelAction::getInfo()->route->value, ['confirm' => '1']),
                ['id' => $entity->id]
            )
        );

        $handler = new TestMessageHandler();
        $messageContext = $handler->createMessageContext([
            SendMessageCommand::class => $handler,
            ParcelDeletedEvent::class => $handler,
        ]);
        $messageContext->setAttribute(new AuthorizeAttribute('11', [UserRole::Manager]));

        $conversation = self::getService(DeleteParcelAction::class)->handle($telegramMessage, $messageContext);

        self::assertEquals(
            ['id' => $entity->id, 'confirm' => true],
            $conversation->playLoad
        );

        /**
         * @var ParcelDeletedEvent $message1
         * @var SendMessageCommand $message2
         */
        [$message1, $message2] = $handler->messages;

        self::assertCount(2, $handler->messages);
        self::assertEquals($entity->id, $message1->parcel->id);
        self::assertEquals('Посылка удалена', $message2->text);

        $manager->getConnection()->rollBack();
    }

    public function testNoAccess(): void
    {
        $entity = new Parcel(
            id: Uuid::v7(),
            authorId: '2',
            status: ParcelStatus::Packed,
            title: '',
            description: '',
        );

        /** @var EntityManagerInterface&ManagerRegistry $manager */
        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->getConnection()->beginTransaction();

        $manager->persist($entity);
        $manager->flush();

        $telegramMessage = new TelegramMessage(
            '1',
            '',
            new Conversation(new ActionRoute(DeleteParcelAction::getInfo()->route->value), ['id' => $entity->id])
        );

        $handler = new TestMessageHandler();
        $messageContext = $handler->createMessageContext([SendMessageCommand::class => $handler]);
        $messageContext->setAttribute(new AuthorizeAttribute('11', [UserRole::User]));

        self::getService(DeleteParcelAction::class)->handle($telegramMessage, $messageContext);

        /** @var SendMessageCommand $message */
        $message = $handler->messages[0];

        self::assertCount(1, $handler->messages);
        self::assertEquals('Удаление не возможно', $message->text);

        $manager->getConnection()->rollBack();
    }
}
