<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository;

use CarVolunteer\Module\Carrier\Delivery\Domain\Delivery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Delivery>
 */
final class DeliveryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Delivery::class);
    }
}
