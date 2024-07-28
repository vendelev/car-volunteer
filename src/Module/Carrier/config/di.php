<?php

declare(strict_types=1);

use CarVolunteer\Module\Carrier\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Domain\SaveParcelCommand;
use CarVolunteer\Module\Carrier\Infrastructure\Repository\ParcelRepository;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\UseCases\CreateParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\EntryPoint\TelegramAction\CreateParcelAction;
use CarVolunteer\Module\Carrier\Parcel\PackParcel\EntryPoint\TelegramAction\PackParcelAction;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\SaveParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler\SaveParcelHandler;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases\ViewParcelsUseCase;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases\ViewParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\EntryPoint\TelegramAction\ViewParcelAction;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\EntryPoint\TelegramAction\ViewParcelsAction;
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

        ->set(ViewParcelsAction::class)
        ->set(ViewParcelsUseCase::class)

        ->set(ViewParcelAction::class)
        ->set(ViewParcelUseCase::class)

        ->set(ParcelRepository::class)
        ->alias(ParcelRepositoryInterface::class, ParcelRepository::class)

        ->set(PackParcelAction::class)
    ;
};
