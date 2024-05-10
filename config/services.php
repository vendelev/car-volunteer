<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TelegramBot\Api\BotApi;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $services->set(BotApi::class)->arg('$token', '%env(TELEGRAM_BOT_TOKEN)%');

    $configurator->import('../src/**/di.php');
};
