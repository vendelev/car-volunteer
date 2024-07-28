<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Parcel\Domain\SaveParcelCommand;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\SaveParcelUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class SaveParcelHandler
{
    public function __construct(
        private SaveParcelUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(SaveParcelCommand $command): void
    {
        $this->useCase->handle($command->userId, $command->parcel);
    }
}