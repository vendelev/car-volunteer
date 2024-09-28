<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Delivery\DeleteDelivery\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionRoute;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Delivery\DeleteDelivery\EntryPoint\TelegramAction\DeleteDeliveryAction;
use CarVolunteer\Module\Carrier\Delivery\Domain\Delivery;
use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use CarVolunteer\Module\Carrier\Domain\DeliveryDeletedEvent;
use CarVolunteer\Tests\Fake\TestMessageHandler;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use HardcorePhp\Infrastructure\Uuid\Uuid;

class DeleteDeliveryActionTest extends KernelTestCaseDecorator
{
    public function testNotDeleted(): void
    {
        $entity = new Delivery(
            id: Uuid::v7(),
            carrierId: '1',
            parcelId: Uuid::v7(),
            status: DeliveryStatus::Delivered,
            deliveryAt: new DateTimeImmutable()
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity);
        $manager->flush();

        $telegramMessage = new TelegramMessage(
            '1',
            '',
            new Conversation(
                new ActionRoute(DeleteDeliveryAction::getInfo()->route->value),
                ['id' => $entity->id->toString()]
            )
        );

        $handler = new TestMessageHandler();
        $messageContext = $handler->createMessageContext([SendMessageCommand::class => $handler]);
        $messageContext->setAttribute(new AuthorizeAttribute('11', [UserRole::Volunteer]));

        self::getService(DeleteDeliveryAction::class)->handle($telegramMessage, $messageContext);

        /** @var list<SendMessageCommand> $messages */
        $messages = $handler->messages;

        self::assertEquals('Удаление не возможно', $messages[0]->text);
    }

    public function testDeleted(): void
    {
        $entity = new Delivery(
            id: Uuid::v7(),
            carrierId: '1',
            parcelId: Uuid::v7(),
            status: DeliveryStatus::WaitDelivery,
            deliveryAt: new DateTimeImmutable()
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity);
        $manager->flush();

        $telegramMessage = new TelegramMessage(
            '1',
            '',
            new Conversation(
                new ActionRoute(DeleteDeliveryAction::getInfo()->route->value, ['confirm' => 1]),
                ['id' => $entity->id->toString()]
            )
        );

        $handler = new TestMessageHandler();
        $messageContext = $handler->createMessageContext([
            SendMessageCommand::class => $handler,
            DeliveryDeletedEvent::class => $handler
        ]);
        $messageContext->setAttribute(new AuthorizeAttribute('11', [UserRole::Volunteer]));

        self::getService(DeleteDeliveryAction::class)->handle($telegramMessage, $messageContext);

        /** @var DeliveryDeletedEvent $message1 */
        $message1 = $handler->messages[0];
        self::assertEquals($entity->id, $message1->deliveryId);

        /** @var SendMessageCommand $message2 */
        $message2 = $handler->messages[1];
        self::assertEquals('Доставка отменена', $message2->text);
    }
}
