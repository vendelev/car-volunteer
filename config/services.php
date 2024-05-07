<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $configurator->import('../src/**/di.php');
    $services->set(TelegramBot\Api\BotApi::class)->arg('$token', '%env(TELEGRAM_BOT_TOKEN)%');
};
