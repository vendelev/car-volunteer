<?php

declare(strict_types=1);

use CarVolunteer\Domain\ActionHandler;
use CarVolunteer\Infrastructure\Telegram\ActionLocator;
use CarVolunteer\Module\Telegram\MessageReceived\Application\MessageEventDataFactory;
use CarVolunteer\Module\Telegram\MessageReceived\Application\UseCases\ParseIncomeMessage;
use CarVolunteer\Module\Telegram\MessageReceived\Application\UseCases\CreateReceiveMessageContext;
use CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\BusHandler\RunActionHandler;
use CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\BusHandler\SendMessageHandler;
use CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\TelegramAction\HelpAction;
use CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\TelegramAction\StartAction;
use CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\Web\WebhookController;
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
        ->instanceof(ActionHandler::class)->tag(ActionHandler::class)
        ->set(ActionLocator::class);

    $services
        ->set(WebhookController::class)
            ->tag('controller.service_arguments')
        ->set(BotApi::class)
            ->arg('$token', '%env(TELEGRAM_BOT_TOKEN)%')

        ->set(ParseIncomeMessage::class)
        ->set(CreateReceiveMessageContext::class)
        ->set(MessageEventDataFactory::class)

        ->set(RunActionHandler::class)
        ->set(SendMessageHandler::class)

        ->set(StartAction::class)
        ->set(HelpAction::class)
    ;
};
