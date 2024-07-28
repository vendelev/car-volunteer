<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Infrastructure\Repository;

use CarVolunteer\Module\Carrier\Packing\Domain\Packing;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PackingRepository extends ServiceEntityRepository implements ParcelRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, string $entityClass = Packing::class)
    {
        parent::__construct($registry, $entityClass);
    }
}