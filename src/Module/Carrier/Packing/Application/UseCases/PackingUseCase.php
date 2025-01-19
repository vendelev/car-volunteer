<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Application\UseCases;

use CarVolunteer\Module\Carrier\Packing\Domain\Packing;
use CarVolunteer\Module\Carrier\Packing\Domain\PackStatus;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Repository\PackingRepository;
use Doctrine\ORM\EntityManagerInterface;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class PackingUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PackingRepository $repository,
    ) {
    }

    public function saveNewPacking(string $pickerId, Uuid $parcelId, Uuid $packingId): void
    {
        $entity = new Packing(
            id: $packingId,
            pickerId: $pickerId,
            parcelId: $parcelId,
            status: PackStatus::Packed
        );

        $this->save($entity);
    }

    public function save(Packing $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function cancelPacking(Uuid $parcelId): void
    {
        $entity = $this->repository->findOneBy(['parcelId' => $parcelId, 'status' => PackStatus::Packed]);

        if ($entity !== null) {
            $entity->status = PackStatus::Cancel;

            $this->save($entity);
        }
    }
}
