<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\FinishDelivery\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Delivery\FinishDelivery\Application\UseCases\SetDeliveredStatusUseCase;
use CarVolunteer\Module\Carrier\Domain\ParcelDeliveredEvent;
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
        $this->useCase->handle($event->deliveryId);
    }
}
