<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelCreatedEvent;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\SaveNewParcelUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class SaveNewParcelHandler
{
    public function __construct(
        private SaveNewParcelUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(ParcelCreatedEvent $command): void
    {
        $this->useCase->handle($command->userId, $command->parcel);
    }
}
