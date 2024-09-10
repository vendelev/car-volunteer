<?php

declare(strict_types=1);

use CarVolunteer\Module\Telegram\User\Application\UseCase\SyncUserUseCase;
use CarVolunteer\Module\Telegram\User\Auth\Middleware\AuthorizeMiddleware;
use CarVolunteer\Module\Telegram\User\EntryPoint\EntryPoint\BusHandler\SyncUserHandler;
use CarVolunteer\Module\Telegram\User\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(UserRepository::class)
        ->set(AuthorizeMiddleware::class)
            ->arg('$roles', '%env(json:file:resolve:ROLES)%')

        ->set(SyncUserHandler::class)
        ->set(SyncUserUseCase::class);
};
