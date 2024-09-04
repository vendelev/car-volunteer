<?php

declare(strict_types=1);

use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\ParcelPlayLoadFactory;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\UseCases\CreateParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\EntryPoint\TelegramAction\CreateParcelAction;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelFilledEvent;
use CarVolunteer\Module\Carrier\Parcel\Infrastructure\Repository\ParcelRepository;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\AssignDeliveryUseCase;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\AssignPackingUseCase;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\SaveNewParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\SetDeliveredStatusUseCase;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler\AssignDeliveryHandler;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler\SetDeliveredStatusHandler;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler\SaveNewParcelHandler;
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
        ->set(SaveNewParcelHandler::class)
        ->set(SaveNewParcelUseCase::class)

        ->set(ViewParcelsAction::class)
        ->set(ViewParcelsUseCase::class)

        ->set(ViewParcelAction::class)
        ->set(ViewParcelUseCase::class)

        ->set(ParcelRepository::class)
        ->alias(ParcelRepositoryInterface::class, ParcelRepository::class)

        ->set(AssignPackingHandler::class)
        ->set(AssignPackingUseCase::class)

        ->set(AssignDeliveryHandler::class)
        ->set(AssignDeliveryUseCase::class)

        ->set(SetDeliveredStatusHandler::class)
        ->set(SetDeliveredStatusUseCase::class)

//        ->set(AssignShippedHandler::class)
//        ->set(AssignShippedUseCase::class)
    ;
};
