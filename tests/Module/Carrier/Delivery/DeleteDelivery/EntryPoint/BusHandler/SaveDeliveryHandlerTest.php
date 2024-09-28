<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Delivery\DeleteDelivery\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Delivery\DeleteDelivery\EntryPoint\BusHandler\SaveDeliveryHandler;
use CarVolunteer\Module\Carrier\Delivery\Domain\Delivery;
use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository\DeliveryRepository;
use CarVolunteer\Module\Carrier\Domain\DeliveryDeletedEvent;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use HardcorePhp\Infrastructure\Uuid\Uuid;

class SaveDeliveryHandlerTest extends KernelTestCaseDecorator
{
    public function testHandleDeliveryDeleted(): void
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

        self::getService(SaveDeliveryHandler::class)->handleDeliveryDeleted(
            new DeliveryDeletedEvent($entity->id, $entity->parcelId)
        );

        /** @var Delivery $result */
        $result = self::getService(DeliveryRepository::class)->findOneBy(['id' => $entity->id]);
        self::assertEquals(DeliveryStatus::Deleted, $result->status);
    }
}
