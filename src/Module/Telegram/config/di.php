<?php

declare(strict_types=1);

use CarVolunteer\Module\Telegram\EntryPoint\BotCommand\StartHandler;
use CarVolunteer\Module\Telegram\EntryPoint\Web\WebhookController;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $services->set(WebhookController::class)
        ->tag('controller.service_arguments');
    $services->set(StartHandler::class)
        ->autowire()
        ->tag('messenger.message_handler');
};
