<?php

declare(strict_types=1);

use CarVolunteer\Module\Carrier\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Domain\SaveParcelCommand;
use CarVolunteer\Module\Carrier\Infrastructure\Repository\ParcelRepository;
use CarVolunteer\Module\Carrier\Packing\Application\PackPlayLoadFactory;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\CreatePackUseCase;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\SavePackUseCase;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\BusHandler\SavePackHandler;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\TelegramAction\CreatePackAction;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\UseCases\CreateParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\EntryPoint\TelegramAction\CreateParcelAction;
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

        ->set(CreatePackAction::class)
        ->set(PackPlayLoadFactory::class)
        ->set(CreatePackUseCase::class)

        ->set(SavePackHandler::class)
        ->set(SavePackUseCase::class)
    ;
};
