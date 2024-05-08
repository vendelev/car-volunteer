<?php

declare(strict_types=1);

use CarVolunteer\Domain\CommandHandler;
use CarVolunteer\Module\Telegram\Application\CommandLocator;
use CarVolunteer\Module\Telegram\EntryPoint\BotCommand\HelpHandler;
use CarVolunteer\Module\Telegram\EntryPoint\BotCommand\StartHandler;
use CarVolunteer\Module\Telegram\EntryPoint\Web\WebhookController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator, ContainerBuilder $builder): void {
    $services = $configurator->services();

    $builder->registerForAutoconfiguration(CommandHandler::class)
        ->addTag(CommandHandler::class);

    $services->set(CommandLocator::class)
        ->autowire();
    $services->set(WebhookController::class)
        ->autowire()
        ->tag('controller.service_arguments');

    $services->set(StartHandler::class)
        ->autowire()
        ->autoconfigure();

    $services->set(HelpHandler::class)
        ->autowire()
        ->autoconfigure();
};
