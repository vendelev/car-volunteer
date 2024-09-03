<?php

declare(strict_types=1);

use CarVolunteer\Module\Carrier\Packing\Application\PackPlayLoadFactory;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\CreatePackUseCase;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\SaveNewPackingUseCase;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\BusHandler\SaveNewPackHandler;
use CarVolunteer\Module\Carrier\Packing\EntryPoint\TelegramAction\CreatePackAction;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Repository\PackingRepository;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\ParcelPlayLoadFactory;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\UseCases\CreateParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\EntryPoint\TelegramAction\CreateParcelAction;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelFilledEvent;
use CarVolunteer\Module\Carrier\Parcel\Infrastructure\Repository\ParcelRepository;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\AssignPackingUseCase;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\SaveParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler\SaveParcelHandler;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler\AssignPackingHandler;
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
        ->set(ParcelPlayLoadFactory::class)
        ->set(CreateParcelUseCase::class)

        ->set(ParcelFilledEvent::class)
        ->set(SaveParcelHandler::class)
        ->set(SaveParcelUseCase::class)

        ->set(ViewParcelsAction::class)
        ->set(ViewParcelsUseCase::class)

        ->set(ViewParcelAction::class)
        ->set(ViewParcelUseCase::class)

        ->set(AssignPackingHandler::class)
        ->set(AssignPackingUseCase::class)

        ->set(ParcelRepository::class)
        ->alias(ParcelRepositoryInterface::class, ParcelRepository::class)

        ->set(CreatePackAction::class)
        ->set(PackPlayLoadFactory::class)
        ->set(CreatePackUseCase::class)
        ->set(PackingRepository::class)

        ->set(SaveNewPackHandler::class)
        ->set(SaveNewPackingUseCase::class)
    ;
};