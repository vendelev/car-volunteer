<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\DeleteDelivery\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository\DeliveryRepository;
use CarVolunteer\Module\Carrier\Domain\DeliveryDeletedEvent;
use Doctrine\ORM\EntityManagerInterface;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class SaveDeliveryHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DeliveryRepository $repository,
    ) {
    }

    #[Handler]
    public function handleDeliveryDeleted(DeliveryDeletedEvent $event): void
    {
        $entity = $this->repository->findOneBy(['id' => $event->deliveryId]);

        if ($entity !== null) {
            $entity->status = DeliveryStatus::Deleted;
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
    }
}
