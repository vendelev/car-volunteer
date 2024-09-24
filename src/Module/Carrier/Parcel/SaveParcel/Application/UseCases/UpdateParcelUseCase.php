<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases;

use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use Doctrine\ORM\EntityManagerInterface;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class UpdateParcelUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ParcelRepositoryInterface $parcelRepository,
    ) {
    }

    public function handle(Uuid $parcelId, ParcelStatus $status): void
    {
        $entity = $this->parcelRepository->findOneBy(['id' => $parcelId]);

        if ($entity !== null) {
            $entity->status = $status->value;

            $this->save($entity);
        }
    }

    public function save(Parcel $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}