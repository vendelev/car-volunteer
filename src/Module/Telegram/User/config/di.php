<?php

declare(strict_types=1);

use CarVolunteer\Module\Telegram\User\Auth\Middleware\AuthorizeMiddleware;
use CarVolunteer\Module\Telegram\User\EntryPoint\MessageCommand\RegisterUserCommand;
use CarVolunteer\Module\Telegram\User\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

//    $services->set(RegisterUserCommand::class)->autowire()->tag('messenger.message_handler');
    $services
        ->set(UserRepository::class)->public()->autowire()
        ->set(AuthorizeMiddleware::class)
            ->arg('$roles', '%env(json:file:resolve:ROLES)%')
    ;
};
