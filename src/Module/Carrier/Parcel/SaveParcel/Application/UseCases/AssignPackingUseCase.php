<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases;

use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class AssignPackingUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ParcelRepositoryInterface $parcelRepository,
    ) {
    }

    public function handle(Uuid $parcelId, Uuid $packingId)
    {
        /** @var Parcel $entity */
        $entity = $this->parcelRepository->findOneBy(['id' => $parcelId]);

        if ($entity !== null) {
            $entity->packingId = $packingId;

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
    }
}