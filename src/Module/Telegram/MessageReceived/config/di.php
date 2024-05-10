<?php

declare(strict_types=1);

use CarVolunteer\Module\Telegram\MessageReceived\Application\Authorization;
use CarVolunteer\Module\Telegram\MessageReceived\Application\CommandLocator;
use CarVolunteer\Module\Telegram\MessageReceived\Domain\CommandHandler;
use CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\BotCommand\CreateOrderHandler;
use CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\BotCommand\HelpHandler;
use CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\BotCommand\StartHandler;
use CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\Web\WebhookController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator, ContainerBuilder $builder): void {
    $services = $configurator->services();

    $services->set(Authorization::class)->arg('$roles', '%env(json:file:resolve:ROLES)%');

    $services->set(WebhookController::class)->autowire()->tag('controller.service_arguments');

//    $builder->registerForAutoconfiguration(CommandHandler::class)->addTag(CommandHandler::class);
    $services->instanceof(CommandHandler::class)->tag(CommandHandler::class);

    $services->set(CommandLocator::class)->autowire();
    $services->set(StartHandler::class)->autowire();
    $services->set(HelpHandler::class)->autowire();
    $services->set(CreateOrderHandler::class)->autowire();
};
