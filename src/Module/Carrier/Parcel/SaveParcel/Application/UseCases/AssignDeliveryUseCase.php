<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases;

use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use Doctrine\ORM\EntityManagerInterface;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class AssignDeliveryUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ParcelRepositoryInterface $parcelRepository,
    ) {
    }

    public function handle(Uuid $parcelId, Uuid $deliveryId): void
    {
        $entity = $this->parcelRepository->findOneBy(['id' => $parcelId]);

        if ($entity !== null) {
            $entity->deliveryId = $deliveryId;
            $entity->status = ParcelStatus::Delivery;

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
    }
}
