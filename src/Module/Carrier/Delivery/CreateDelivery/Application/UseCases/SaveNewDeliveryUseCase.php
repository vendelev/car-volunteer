<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\UseCases;

use CarVolunteer\Module\Carrier\Delivery\Domain\Delivery;
use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class SaveNewDeliveryUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(string $carrierId, Uuid $parcelId, Uuid $deliveryId, DateTimeImmutable $deliveryDate): void
    {
        $entity = new Delivery(
            id: $deliveryId,
            carrierId: $carrierId,
            parcelId: $parcelId,
            status: DeliveryStatus::WaitDelivery->value,
            deliveryAt: $deliveryDate,
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
