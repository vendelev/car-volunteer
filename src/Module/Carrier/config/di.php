<?php

declare(strict_types=1);

use CarVolunteer\Module\Carrier\CreateParcel\Application\UseCases\CreateParcelUseCase;
use CarVolunteer\Module\Carrier\CreateParcel\EntryPoint\TelegramAction\CreateParcelAction;
use CarVolunteer\Module\Carrier\CreateParcel\EntryPoint\TelegramAction\PackParcelAction;
use CarVolunteer\Module\Carrier\Domain\SaveParcelCommand;
use CarVolunteer\Module\Carrier\SaveParcel\Application\UseCases\SaveParcelUseCase;
use CarVolunteer\Module\Carrier\SaveParcel\EntryPoint\BusHandler\SaveParcelHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(CreateParcelAction::class)
        ->set(CreateParcelUseCase::class)

        ->set(SaveParcelCommand::class)
        ->set(SaveParcelHandler::class)
        ->set(SaveParcelUseCase::class)

        ->set(PackParcelAction::class)
    ;
};
