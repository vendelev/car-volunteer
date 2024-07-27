<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\SaveParcel\Application\UseCases;

use CarVolunteer\Module\Carrier\Domain\Entity\Parcel as ParcelEntity;
use CarVolunteer\Module\Carrier\Domain\Dto\Parcel as ParcelDto;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SaveParcelUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(string $userId, ParcelDto $parcel): void
    {
        $entity = new ParcelEntity(
            id: $parcel->id,
            authorId: $userId,
            status: $parcel->status->value,
            description: $parcel->description,
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

}