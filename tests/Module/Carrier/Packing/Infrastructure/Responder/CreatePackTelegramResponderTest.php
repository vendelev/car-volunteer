<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Packing\Infrastructure\Responder;

use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Packing\Domain\PackStatus;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder\CreatePackTelegramResponder;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class CreatePackTelegramResponderTest extends KernelTestCaseDecorator
{
    public function testNoAccess(): void
    {
        $result = self::getService(CreatePackTelegramResponder::class)->createPack(
            userId: 'test',
            status: PackStatus::New,
            parcelId: Uuid::nil(),
            roles: [UserRole::User]
        );

        self::assertCount(0, $result);
    }

    #[DataProvider('dataProvider')]
    public function testCreatePack(PackStatus $status, UserRole $role, int $expected): void
    {
        $result = self::getService(CreatePackTelegramResponder::class)->createPack(
            userId: 'test',
            status: $status,
            parcelId: Uuid::nil(),
            roles: [$role, UserRole::User]
        );

        /** @var InlineKeyboardMarkup $replyMarkup */
        $replyMarkup = $result[0]->replyMarkup;

        if ($expected !== 0) {
            self::assertCount($expected, $replyMarkup->getInlineKeyboard());
        } else {
            self::assertNull($replyMarkup);
        }
    }

    /**
     * @return iterable<array{status: PackStatus, role: UserRole, expected: int}>
     */
    public static function dataProvider(): iterable
    {
        yield 'Начинаем процесс упаковки' => [
            'status' => PackStatus::New,
            'role' => UserRole::Manager,
            'expected' => 3
        ];

        yield 'Ждем фото' => [
            'status' => PackStatus::WaitPhoto,
            'role' => UserRole::Picker,
            'expected' => 0
        ];

        yield 'Фото загружено' => [
            'status' => PackStatus::PhotoLoaded,
            'role' => UserRole::Manager,
            'expected' => 2
        ];

        yield 'Ждем подтверждения готовности посылки' => [
            'status' => PackStatus::WaitPack,
            'role' => UserRole::Picker,
            'expected' => 2
        ];

        yield 'Посылка собрана' => [
            'status' => PackStatus::Packed,
            'role' => UserRole::Picker,
            'expected' => 2
        ];
    }
}
