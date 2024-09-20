<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository;

use CarVolunteer\Module\Carrier\Delivery\Domain\Delivery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T of Delivery
 * @extends ServiceEntityRepository<T>
 */
final class DeliveryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        /** @var class-string<T> $entityClass */
        $entityClass = Delivery::class;

        parent::__construct($registry, $entityClass);
    }
}
