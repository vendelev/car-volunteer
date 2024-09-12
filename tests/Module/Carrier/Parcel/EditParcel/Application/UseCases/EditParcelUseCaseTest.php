<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Parcel\EditParcel\Application\UseCases;

use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Application\UseCases\EditParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\Infrastructure\Repository\ParcelRepository;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;

class EditParcelUseCaseTest extends KernelTestCaseDecorator
{
    #[DataProvider('dataProvider')]
    public function testReturnNull(Uuid $parcelId, string $userId): void
    {
        self::assertNull(
            self::getService(EditParcelUseCase::class)->handle($userId, $parcelId->toString(), [], null)
        );

        /** @var Parcel|null $parcel */
        $parcel = self::getService(ParcelRepository::class)->findOneBy(['id' => $parcelId->toString()]);
        if ($parcel) {
            self::assertEquals('test', $parcel->description);
        }
    }

    /**
     * @return iterable<array{parcelId: Uuid, userId: string}>
     */
    public static function dataProvider(): iterable
    {
        $entity = new Parcel(
            id: Uuid::v7(),
            authorId: '1',
            status: ParcelStatus::Described->value,
            title: '',
            description: 'test',
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity);
        $manager->flush();

        yield 'Нет записи' => ['parcelId' => Uuid::v7((new DateTimeImmutable())->modify('-1 day')), 'userId' => '1'];
        yield 'Нет прав' => ['parcelId' => $entity->id, 'userId' => '2'];

        $entity = new Parcel(
            id: Uuid::v7(),
            authorId: '2',
            status: ParcelStatus::Packed->value,
            title: '',
            description: 'test',
        );
        $manager->persist($entity);
        $manager->flush();
        yield 'Не тот статус' => ['parcelId' => $entity->id, 'userId' => '2'];
    }

    public function testChangeStatus(): void
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

        $result = self::getService(EditParcelUseCase::class)->handle('1', $entity->id->toString(), [], 'test');

        self::assertNotNull($result);
        self::assertEquals(ParcelStatus::WaitDescription->value, $result->status);
    }

    public function testUpdateDescription(): void
    {
        $entity = new Parcel(
            id: Uuid::v7(),
            authorId: '2',
            status: ParcelStatus::WaitDescription->value,
            title: '',
            description: 'test',
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity);
        $manager->flush();

        $result = self::getService(EditParcelUseCase::class)->handle('2', $entity->id->toString(), [], 'test2');

        self::assertNull($result);

        /** @var Parcel $parcel */
        $parcel = self::getService(ParcelRepository::class)->findOneBy(['id' => $entity->id->toString()]);

        self::assertEquals(ParcelStatus::Described->value, $parcel->status);
        self::assertEquals('test2', $parcel->description);
    }
}
