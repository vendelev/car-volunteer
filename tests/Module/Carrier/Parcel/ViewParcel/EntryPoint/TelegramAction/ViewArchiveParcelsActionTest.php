<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Parcel\ViewParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionRoute;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\EntryPoint\TelegramAction\ViewArchiveParcelsAction;
use CarVolunteer\Tests\Fake\TestMessageHandler;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class ViewArchiveParcelsActionTest extends KernelTestCaseDecorator
{
    public function testHandle(): void
    {
        $entity1 = new Parcel(
            id: Uuid::v7(),
            authorId: '2',
            status: ParcelStatus::Delivered,
            title: '',
            description: '',
            createAt: new DateTimeImmutable()
        );
        $entity2 = new Parcel(
            id: Uuid::v7(),
            authorId: '1',
            status: ParcelStatus::Delivered,
            title: '',
            description: '',
            createAt: (new DateTimeImmutable())->modify('-15 days')
        );
        $entity3 = new Parcel(
            id: Uuid::v7(),
            authorId: '3',
            status: ParcelStatus::Delivered,
            title: '',
            description: '',
            createAt: (new DateTimeImmutable())->modify('-14 days')
        );

        /** @var EntityManagerInterface&ManagerRegistry $manager */
        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->getConnection()->beginTransaction();
        $manager->getConnection()->executeQuery('truncate carrier.parcel');

        $manager->persist($entity1);
        $manager->persist($entity2);
        $manager->persist($entity3);
        $manager->flush();

        $telegramMessage = new TelegramMessage(
            '1',
            '',
            new Conversation(new ActionRoute(ViewArchiveParcelsAction::getInfo()->route->value))
        );

        $handler = new TestMessageHandler();
        $messageContext = $handler->createMessageContext([SendMessageCommand::class => $handler]);
        $messageContext->setAttribute(new AuthorizeAttribute('11', [UserRole::User]));

        self::getService(ViewArchiveParcelsAction::class)->handle($telegramMessage, $messageContext);

        /** @var list<SendMessageCommand> $messages */
        $messages = $handler->messages;

        self::assertCount(1, $messages);

        /** @var InlineKeyboardMarkup $replyMarkup */
        $replyMarkup = $messages[0]->replyMarkup;
        $buttons = $replyMarkup->getInlineKeyboard();

        self::assertCount(3, $buttons);
        self::assertStringContainsString($entity3->id->toString(), $buttons[0][0]['callback_data']);
        self::assertStringContainsString($entity1->id->toString(), $buttons[1][0]['callback_data']);

        $manager->getConnection()->rollBack();
    }
}
