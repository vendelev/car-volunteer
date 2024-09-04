<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\CreateDelivery\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\UseCases\NotifyNewDeliveryUseCase;
use CarVolunteer\Module\Carrier\Domain\DeliveryCreatedEvent;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class NotifyNewDeliveryHandler
{
    public function __construct(
        private NotifyNewDeliveryUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(DeliveryCreatedEvent $event): void
    {
        $this->useCase->handle($event->carrierId, $event->parcelId);
    }
}