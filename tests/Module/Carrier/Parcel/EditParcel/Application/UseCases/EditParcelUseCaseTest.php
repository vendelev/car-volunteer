<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Parcel\EditParcel\Application\UseCases;

use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelPlayLoad;
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
    public function testReturnNull(ParcelPlayLoad $playLoad, string $userId): void
    {
        self::assertNull(
            self::getService(EditParcelUseCase::class)->handle($userId, $playLoad, [], null)
        );

        /** @var Parcel|null $parcel */
        $parcel = self::getService(ParcelRepository::class)->findOneBy(['id' => $playLoad->id->toString()]);
        if ($parcel) {
            self::assertEquals('test', $parcel->description);
        }
    }

    /**
     * @return iterable<array{playLoad: ParcelPlayLoad, userId: string}>
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

        $playLoad = new ParcelPlayLoad(
            Uuid::v7((new DateTimeImmutable())->modify('-1 day')),
            ParcelStatus::from($entity->status)
        );

        yield 'Нет записи' => ['playLoad' => $playLoad, 'userId' => '1'];

        $playLoad = new ParcelPlayLoad(
            $entity->id,
            ParcelStatus::from($entity->status)
        );
        yield 'Нет прав' => ['playLoad' => $playLoad, 'userId' => '2'];

        $entity = new Parcel(
            id: Uuid::v7(),
            authorId: '2',
            status: ParcelStatus::Packed->value,
            title: '',
            description: 'test',
        );
        $manager->persist($entity);
        $manager->flush();
        $playLoad = new ParcelPlayLoad(
            $entity->id,
            ParcelStatus::from($entity->status)
        );

        yield 'Не тот статус' => ['playLoad' => $playLoad, 'userId' => '2'];
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

        $playLoad = new ParcelPlayLoad(
            $entity->id,
            ParcelStatus::New
        );

        $result = self::getService(EditParcelUseCase::class)->handle('1', $playLoad, [], 'test');

        self::assertNotNull($result);
        self::assertEquals(ParcelStatus::WaitDescription, $result->status);

        /** @var Parcel $parcel */
        $parcel = self::getService(ParcelRepository::class)->findOneBy(['id' => $entity->id->toString()]);
        self::assertEquals(ParcelStatus::Described->value, $parcel->status);
    }

    public function testUpdateDescription(): void
    {
        $entity = new Parcel(
            id: Uuid::v7(),
            authorId: '2',
            status: ParcelStatus::Described->value,
            title: '',
            description: 'test',
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity);
        $manager->flush();

        $playLoad = new ParcelPlayLoad(
            $entity->id,
            ParcelStatus::WaitDescription
        );

        $result = self::getService(EditParcelUseCase::class)->handle('2', $playLoad, [], 'test2');

        self::assertNotNull($result);
        self::assertEquals(ParcelStatus::Described, $result->status);

        /** @var Parcel $parcel */
        $parcel = self::getService(ParcelRepository::class)->findOneBy(['id' => $entity->id->toString()]);

        self::assertEquals('test2', $parcel->description);
    }
}
