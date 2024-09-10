<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Domain\ParcelDeliveredEvent;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\SetDeliveredStatusUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class SetDeliveredStatusHandler
{
    public function __construct(
        private SetDeliveredStatusUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(ParcelDeliveredEvent $event): void
    {
        $this->useCase->handle($event->parcelId);
    }
}
