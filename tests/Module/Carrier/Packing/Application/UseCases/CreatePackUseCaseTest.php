<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Packing\Application\UseCases;

use CarVolunteer\Module\Carrier\Domain\ParcelPackedEvent;
use CarVolunteer\Module\Carrier\Packing\Application\PackStateMachine;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\CreatePackUseCase;
use CarVolunteer\Module\Carrier\Packing\Domain\Packing;
use CarVolunteer\Module\Carrier\Packing\Domain\PackPlayLoad;
use CarVolunteer\Module\Carrier\Packing\Domain\PackStatus;
use CarVolunteer\Module\Carrier\Packing\Domain\UserClickEvent;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Repository\PackingRepository;
use CarVolunteer\Tests\Fake\TestMessageHandler;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use Doctrine\Persistence\ManagerRegistry;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\MessageBus\HandlerRegistry\ArrayHandlerRegistry;
use Telephantast\MessageBus\MessageBus;

class CreatePackUseCaseTest extends KernelTestCaseDecorator
{
    public function testAlreadyPacked(): void
    {
        $entity = new Packing(
            id: Uuid::v7(),
            pickerId: 'pickerId',
            parcelId: Uuid::v7(),
            status: PackStatus::Packed->value,
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity);
        $manager->flush();

        $result = self::getService(CreatePackUseCase::class)->handle(
            '1',
            new PackPlayLoad(
                id: Uuid::v7(),
                parcelId: $entity->parcelId,
                status: PackStatus::New
            ),
            null,
            null
        );

        self::assertEquals(PackStatus::Packed, $result->status);
    }

    public function testPhotoLoaded(): void
    {
        $result = self::getService(CreatePackUseCase::class)->handle(
            '1',
            new PackPlayLoad(
                id: Uuid::v7(),
                parcelId: Uuid::v7(),
                status: PackStatus::WaitPhoto
            ),
            null,
            'test-id'
        );

        self::assertEquals(PackStatus::PhotoLoaded, $result->status);
        self::assertEquals('test-id', $result->photoId);
    }

    public function testPacked(): void
    {
        $handler = new TestMessageHandler();
        $bus = new MessageBus(new ArrayHandlerRegistry([ParcelPackedEvent::class => $handler]));

        $useCase = new CreatePackUseCase(
            self::getService(PackingRepository::class),
            self::getService(PackStateMachine::class),
            $bus
        );
        $result = $useCase->handle(
            '1',
            new PackPlayLoad(
                id: Uuid::v7(),
                parcelId: Uuid::v7(),
                status: PackStatus::WaitPack
            ),
            UserClickEvent::Packed,
            null
        );

        self::assertEquals(PackStatus::Packed, $result->status);
        self::assertCount(1, $handler->messages);
    }
}
