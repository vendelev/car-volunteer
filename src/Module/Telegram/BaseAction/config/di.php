<?php

declare(strict_types=1);

use CarVolunteer\Module\Telegram\BaseAction\EntryPoint\TelegramAction\HelpAction;
use CarVolunteer\Module\Telegram\BaseAction\EntryPoint\TelegramAction\StartAction;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(StartAction::class)
        ->set(HelpAction::class)
    ;
};
