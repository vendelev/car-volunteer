<?php

declare(strict_types=1);

use CarVolunteer\Module\Carrier\Packing\Application\PackPlayLoadFactory;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\CreatePackUseCase;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\SaveNewPackingUseCase;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\BusHandler\SaveNewPackHandler;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\TelegramAction\CreatePackAction;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Repository\PackingRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(CreatePackAction::class)
        ->set(PackPlayLoadFactory::class)
        ->set(CreatePackUseCase::class)
        ->set(PackingRepository::class)

        ->set(SaveNewPackHandler::class)
        ->set(SaveNewPackingUseCase::class);
};
