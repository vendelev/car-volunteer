<?php

declare(strict_types=1);

use CarVolunteer\Component\Photo\EntryPoint\BusHandler\PhotoHandler;
use CarVolunteer\Component\Photo\Infrastructure\Repository\PhotoRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(PhotoHandler::class)
        ->set(PhotoRepository::class);
};
