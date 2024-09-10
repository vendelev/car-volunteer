<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository;

use CarVolunteer\Module\Carrier\Delivery\Domain\Delivery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DeliveryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass = Delivery::class)
    {
        parent::__construct($registry, $entityClass);
    }
}
