<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Application\UseCases;

use CarVolunteer\Module\Carrier\Packing\Domain\Packing;
use CarVolunteer\Module\Carrier\Packing\Domain\PackPlayLoad;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SavePackUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(string $userId, PackPlayLoad $playLoad): void
    {
        $entity = new Packing(
            id: $playLoad->id,
            pickerId: $userId,
            parcelId: $playLoad->parcelId,
            status: $playLoad->status->value,
            createAt: new DateTimeImmutable,
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}