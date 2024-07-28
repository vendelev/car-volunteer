<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\Infrastructure\Repository;

use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ParcelRepository extends ServiceEntityRepository implements ParcelRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, string $entityClass = Parcel::class)
    {
        parent::__construct($registry, $entityClass);
    }
}