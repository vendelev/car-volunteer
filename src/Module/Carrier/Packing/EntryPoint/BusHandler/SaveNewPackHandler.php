<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Domain\ParcelPackedEvent;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\SaveNewPackingUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class SaveNewPackHandler
{
    public function __construct(
        private SaveNewPackingUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(ParcelPackedEvent $event): void
    {
        $this->useCase->handle(
            pickerId: $event->pickerId,
            parcelId: $event->parcelId,
            packingId: $event->packingId,
            photoId: $event->photoId
        );
    }
}
