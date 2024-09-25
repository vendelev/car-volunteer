<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Domain\DeliveryDeletedEvent;
use CarVolunteer\Module\Carrier\Domain\ParcelDeliveredEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelChangeDescriptionEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelChangeTitleEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelDeletedEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\UpdateParcelUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class SaveParcelHandler
{
    public function __construct(
        private UpdateParcelUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handleParcelDelivered(ParcelDeliveredEvent $event): void
    {
        $this->useCase->handle($event->parcelId, ParcelStatus::Delivered);
    }

    #[Handler]
    public function handleParcelChange(
        ParcelDeletedEvent|ParcelChangeTitleEvent|ParcelChangeDescriptionEvent $event
    ): void {
        $this->useCase->save($event->parcel);
    }

    #[Handler]
    public function handleDeliveryDeleted(DeliveryDeletedEvent $event): void
    {
        $this->useCase->deleteDelivery($event->parcelId);
    }
}
