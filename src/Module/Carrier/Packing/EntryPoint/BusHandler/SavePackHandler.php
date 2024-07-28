<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Packing\Application\UseCases\SavePackUseCase;
use CarVolunteer\Module\Carrier\Packing\Domain\SavePackCommand;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class SavePackHandler
{
    public function __construct(
        private SavePackUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(SavePackCommand $command): void
    {
        $this->useCase->handle($command->userId, $command->pack);
    }
}