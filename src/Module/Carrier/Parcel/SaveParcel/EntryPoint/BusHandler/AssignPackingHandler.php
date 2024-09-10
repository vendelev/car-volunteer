<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Domain\ParcelPackedEvent;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\AssignPackingUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class AssignPackingHandler
{
    public function __construct(
        private AssignPackingUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(ParcelPackedEvent $event): void
    {
        $this->useCase->handle($event->parcelId, $event->packingId);
    }
}
