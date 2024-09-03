<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Domain\DeliveryCreatedEvent;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\AssignDeliveryUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class AssignDeliveryHandler
{
    public function __construct(
        private AssignDeliveryUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(DeliveryCreatedEvent $event): void
    {
        $this->useCase->handle($event->parcelId, $event->deliveryId);
    }
}