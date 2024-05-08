<?php

declare(strict_types=1);

use CarVolunteer\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TelegramBot\Api\BotApi;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $configurator->import('../src/**/di.php');
    $services->set(BotApi::class)->arg('$token', '%env(TELEGRAM_BOT_TOKEN)%');
    $services->set(UserRepository::class)->public()->autowire();
};
