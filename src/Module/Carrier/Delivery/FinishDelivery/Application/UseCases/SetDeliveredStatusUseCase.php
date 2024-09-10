<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\FinishDelivery\Application\UseCases;

use CarVolunteer\Module\Carrier\Delivery\Domain\Delivery;
use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository\DeliveryRepository;
use Doctrine\ORM\EntityManagerInterface;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class SetDeliveredStatusUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DeliveryRepository $repository,
    ) {
    }

    public function handle(Uuid $deliveryId): void
    {
        /** @var Delivery|null $entity */
        $entity = $this->repository->find(['id' => $deliveryId]);

        if ($entity !== null) {
            $entity->status = DeliveryStatus::Delivered->value;

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
    }
}
