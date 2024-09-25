<?php

declare(strict_types=1);

use CarVolunteer\Module\Carrier\Application\DeletePlayLoadFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(DeletePlayLoadFactory::class);
};
