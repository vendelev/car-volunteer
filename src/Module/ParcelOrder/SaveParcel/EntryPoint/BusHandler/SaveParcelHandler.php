<?php

declare(strict_types=1);

namespace CarVolunteer\Module\ParcelOrder\SaveParcel\EntryPoint\BusHandler;

use CarVolunteer\Module\ParcelOrder\Domain\SaveParcelCommand;
use CarVolunteer\Module\ParcelOrder\SaveParcel\Application\UseCases\SaveParcelUseCase;
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