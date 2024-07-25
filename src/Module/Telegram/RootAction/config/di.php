<?php

declare(strict_types=1);

use CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction\CreateAction;
use CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction\HelpAction;
use CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction\StartAction;
use CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction\ViewAction;
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
        ->set(CreateAction::class)
        ->set(ViewAction::class)
    ;
};
