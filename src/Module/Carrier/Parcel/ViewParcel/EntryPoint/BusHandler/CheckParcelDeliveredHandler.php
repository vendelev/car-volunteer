<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Domain\CheckParcelDeliveredQuery;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases\ViewParcelUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class CheckParcelDeliveredHandler
{
    public function __construct(
        private ViewParcelUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handleQuery(CheckParcelDeliveredQuery $query): bool
    {
        return $this->useCase->checkParcelDelivered($query->parcelId);
    }
}
