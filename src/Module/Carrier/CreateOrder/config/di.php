<?php

declare(strict_types=1);

use CarVolunteer\Module\Telegram\EntryPoint\BotCommand\CreateOrderHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

//    $services->set(CreateOrderHandler::class)
//        ->autowire()
//        ->autoconfigure();
};
