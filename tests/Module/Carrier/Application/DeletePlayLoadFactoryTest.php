<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Application;

use CarVolunteer\Domain\ActionRoute;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Module\Carrier\Application\DeletePlayLoadFactory;
use CarVolunteer\Module\Carrier\Domain\DeletePlayLoad;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DeletePlayLoadFactoryTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testCreateFromConversation(Conversation $conversation, DeletePlayLoad $expected): void
    {
        self::assertEquals(
            $expected,
            (new DeletePlayLoadFactory())->createFromConversation($conversation)
        );
    }

    /**
     * @return iterable<string, array{conversation: Conversation, expected: DeletePlayLoad}>
     */
    public static function dataProvider(): iterable
    {
        yield 'Кривой запрос' => [
            'conversation' => new Conversation(new ActionRoute('')),
            'expected' => new DeletePlayLoad(Uuid::nil(), false)
        ];

        yield 'Запрос на удаление' => [
            'conversation' => new Conversation(new ActionRoute('', ['id' => Uuid::max()->toString()])),
            'expected' => new DeletePlayLoad(Uuid::max(), false)
        ];

        yield 'Подтверждение удаления' => [
            'conversation' => new Conversation(
                new ActionRoute('', ['confirm' => '1']),
                ['id' => Uuid::max()->toString()]
            ),
            'expected' => new DeletePlayLoad(Uuid::max(), true)
        ];

        yield 'ID не совпали' => [
            'conversation' => new Conversation(
                new ActionRoute('', ['confirm' => '1', 'id' => Uuid::max()->toString()]),
                ['id' => Uuid::v7()]
            ),
            'expected' => new DeletePlayLoad(Uuid::max(), false)
        ];
    }
}
