<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Domain\CancelPackingCommand;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\PackingUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class CancelPackHandler
{
    public function __construct(
        private PackingUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(CancelPackingCommand $command): void
    {
        $this->useCase->cancelPacking($command->parcelId);
    }
}
