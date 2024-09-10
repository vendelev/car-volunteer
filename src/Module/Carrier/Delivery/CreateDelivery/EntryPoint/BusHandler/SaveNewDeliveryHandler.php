<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\CreateDelivery\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\UseCases\SaveNewDeliveryUseCase;
use CarVolunteer\Module\Carrier\Domain\DeliveryCreatedEvent;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class SaveNewDeliveryHandler
{
    public function __construct(
        private SaveNewDeliveryUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(DeliveryCreatedEvent $event): void
    {
        $this->useCase->handle($event->carrierId, $event->deliveryId, $event->parcelId);
    }
}
