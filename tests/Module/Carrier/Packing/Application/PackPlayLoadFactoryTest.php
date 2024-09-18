<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Packing\Application;

use CarVolunteer\Module\Carrier\Packing\Application\PackPlayLoadFactory;
use CarVolunteer\Module\Carrier\Packing\Domain\PackStatus;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;

class PackPlayLoadFactoryTest extends KernelTestCaseDecorator
{
    /**
     * @param array<mixed>|null $playLoad
     */
    #[DataProvider('dataProvider')]
    public function testCreateFromConversation(?array $playLoad, PackStatus $status, ?string $photoId): void
    {
        $result = self::getService(PackPlayLoadFactory::class)->createFromConversation(
            Uuid::nil()->toString(),
            $playLoad
        );

        self::assertEquals($status, $result->status);
        self::assertEquals($photoId, $result->photoId);
    }

    /**
     * @return iterable<array{playLoad: array<mixed>|null, status: PackStatus, photoId: string|null}>
     */
    public static function dataProvider(): iterable
    {
        yield 'Новый объект' => [
            'playLoad' => null,
            'status' => PackStatus::New,
            'photoId' => null,
        ];

        yield 'Денормализация объекта' => [
            'playLoad' => [
                'id' => Uuid::max()->toString(),
                'parcelId' => Uuid::max()->toString(),
                'status' => PackStatus::WaitPack->value,
                'photoId' => 'testId'
            ],
            'status' => PackStatus::WaitPack,
            'photoId' => 'testId',
        ];
    }
}
