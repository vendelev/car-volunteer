<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder;

use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelPlayLoad;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder\EditParcelTelegramResponder;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EditParcelTelegramResponderTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testGetMessages(?ParcelPlayLoad $entity, int $expected): void
    {
        $commands = (new EditParcelTelegramResponder())->getEditMessages('', $entity);
        self::assertCount($expected, $commands);
    }

    /**
     * @return iterable<array{entity: ParcelPlayLoad|null, expected: int}>
     */
    public static function dataProvider(): iterable
    {
        yield 'Ничего отправлять не надо' => ['entity' => null, 'expected' => 0];
        yield 'Создается 2 сообщения' => [
            'entity' => new ParcelPlayLoad(
                id: Uuid::nil(),
                status: ParcelStatus::WaitDescription,
                title: '',
                description: 'test',
            ),
            'expected' => 2
        ];
        yield 'Создается 1 сообщения' => [
            'entity' => new ParcelPlayLoad(
                id: Uuid::nil(),
                status: ParcelStatus::Described,
                title: '',
                description: 'test',
            ),
            'expected' => 1
        ];
    }
}
