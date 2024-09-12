<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder;

use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder\EditParcelTelegramResponder;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EditParcelTelegramResponderTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testGetMessages(?Parcel $entity, int $expected): void
    {
        $commands = (new EditParcelTelegramResponder())->getMessages('', $entity);
        self::assertCount($expected, $commands);
    }

    /**
     * @return iterable<array{entity: Parcel|null, expected: int}>
     */
    public static function dataProvider(): iterable
    {
        yield 'Ничего отправлять не надо' => ['entity' => null, 'expected' => 0];
        yield 'Создается 2 сообщения' => [
            'entity' => new Parcel(
                id: Uuid::nil(),
                authorId: '',
                status: '',
                title: '',
                description: 'test',
            ),
            'expected' => 2
        ];
    }
}
