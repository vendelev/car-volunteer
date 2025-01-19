<?php

declare(strict_types=1);

use CarVolunteer\Module\Carrier\Packing\Application\PackPlayLoadFactory;
use CarVolunteer\Module\Carrier\Packing\Application\PackStateMachine;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\CreatePackUseCase;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\PackingUseCase;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\BusHandler\CancelPackHandler;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\BusHandler\SaveNewPackHandler;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\TelegramAction\CancelPackingAction;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\TelegramAction\CreatePackAction;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\TelegramAction\ViewPackingPhotoAction;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Repository\PackingRepository;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder\CancelPackingTelegramResponder;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder\CreatePackTelegramResponder;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder\ViewPackingPhotoTelegramResponder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(CreatePackAction::class)
        ->set(PackPlayLoadFactory::class)
        ->set(PackStateMachine::class)
        ->set(CreatePackUseCase::class)
        ->set(PackingRepository::class)
        ->set(CreatePackTelegramResponder::class)

        ->set(CancelPackingAction::class)
        ->set(CancelPackingTelegramResponder::class)
        ->set(CancelPackHandler::class)

        ->set(ViewPackingPhotoAction::class)
        ->set(ViewPackingPhotoTelegramResponder::class)

        ->set(SaveNewPackHandler::class)
        ->set(PackingUseCase::class);
};
