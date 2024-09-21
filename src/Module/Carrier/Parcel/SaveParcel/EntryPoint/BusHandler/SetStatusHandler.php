<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Domain\ParcelDeliveredEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelDeletedEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\SetStatusUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class SetStatusHandler
{
    public function __construct(
        private SetStatusUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handleParcelDelivered(ParcelDeliveredEvent $event): void
    {
        $this->useCase->handle($event->parcelId, ParcelStatus::Delivered);
    }

    #[Handler]
    public function handleParcelDeletedEvent(ParcelDeletedEvent $event): void
    {
        $this->useCase->handle($event->parcelId, ParcelStatus::Deleted);
    }
}
