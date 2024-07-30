<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Application\UseCases;

use CarVolunteer\Module\Carrier\Packing\Domain\Packing;
use CarVolunteer\Module\Carrier\Packing\Domain\PackStatus;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class SaveNewPackingUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(string $userId, Uuid $parcelId, Uuid $packingId): void
    {
        $entity = new Packing(
            id: $packingId,
            pickerId: $userId,
            parcelId: $parcelId,
            status: PackStatus::Packed->value,
            createAt: new DateTimeImmutable,
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}