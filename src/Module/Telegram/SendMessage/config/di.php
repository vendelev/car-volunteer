<?php

declare(strict_types=1);

use CarVolunteer\Module\Telegram\SendMessage\EntryPoint\BusHandler\AnswerCallbackQueryHandler;
use CarVolunteer\Module\Telegram\SendMessage\EntryPoint\BusHandler\SendMessageHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TelegramBot\Api\BotApi;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(BotApi::class)
            ->arg('$token', '%env(TELEGRAM_BOT_TOKEN)%')

        ->set(SendMessageHandler::class)
        ->set(AnswerCallbackQueryHandler::class)
    ;
};
