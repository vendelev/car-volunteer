<?php

declare(strict_types=1);

use CarVolunteer\Module\Telegram\ReceiveMessage\Application\MessageEventDataFactory;
use CarVolunteer\Module\Telegram\ReceiveMessage\Application\IncomeMessageParser;
use CarVolunteer\Module\Telegram\ReceiveMessage\Application\ReceiveMessageEventFactory;
use CarVolunteer\Module\Telegram\ReceiveMessage\EntryPoint\BusHandler\RunActionHandler;
use CarVolunteer\Module\Telegram\ReceiveMessage\EntryPoint\Web\WebhookController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TelegramBot\Api\BotApi;

return static function (ContainerConfigurator $configurator, ContainerBuilder $builder): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(WebhookController::class)
            ->tag('controller.service_arguments')
        ->set(BotApi::class)
            ->arg('$token', '%env(TELEGRAM_BOT_TOKEN)%')

        ->set(IncomeMessageParser::class)
        ->set(ReceiveMessageEventFactory::class)
        ->set(MessageEventDataFactory::class)

        ->set(RunActionHandler::class)
    ;
};
