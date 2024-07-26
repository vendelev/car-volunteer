<?php

declare(strict_types=1);

namespace CarVolunteer\Module\ParcelOrder\Infrastructure\Repository;

use CarVolunteer\Module\ParcelOrder\Domain\Dto\Parcel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ParcelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass = Parcel::class)
    {
        parent::__construct($registry, $entityClass);
    }
}