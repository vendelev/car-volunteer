<?php

declare(strict_types=1);

use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\ParcelPlayLoadFactory;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\UseCases\CreateParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\EntryPoint\BusHandler\NotifyNewParcelHandler;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\EntryPoint\TelegramAction\CreateParcelAction;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Infrastructure\Responder\NotifyTelegramResponder;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelCreatedEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Application\UseCases\EditParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\EntryPoint\TelegramAction\EditParcelAction;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder\EditParcelTelegramResponder;
use CarVolunteer\Module\Carrier\Parcel\Infrastructure\Repository\ParcelRepository;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\AssignDeliveryUseCase;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\AssignPackingUseCase;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\Application\UseCases\SaveNewParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler\AssignDeliveryHandler;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler\AssignPackingHandler;
use CarVolunteer\Module\Carrier\Parcel\SaveParcel\EntryPoint\BusHandler\SaveNewParcelHandler;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases\ViewParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\EntryPoint\TelegramAction\ViewArchiveParcelsAction;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\EntryPoint\TelegramAction\ViewParcelAction;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\EntryPoint\TelegramAction\ViewParcelsAction;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Infrastructure\Responder\ViewParcelTelegramResponder;
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

        ->set(ParcelCreatedEvent::class)
        ->set(SaveNewParcelHandler::class)
        ->set(SaveNewParcelUseCase::class)

        ->set(ViewParcelsAction::class)
        ->set(ViewParcelAction::class)
        ->set(ViewParcelUseCase::class)
        ->set(ViewParcelTelegramResponder::class)

        ->set(ParcelRepository::class)
        ->alias(ParcelRepositoryInterface::class, ParcelRepository::class)

        ->set(AssignPackingHandler::class)
        ->set(AssignPackingUseCase::class)

        ->set(AssignDeliveryHandler::class)
        ->set(AssignDeliveryUseCase::class)

        ->set(EditParcelAction::class)
        ->set(EditParcelUseCase::class)
        ->set(EditParcelTelegramResponder::class)

        ->set(NotifyTelegramResponder::class)
        ->set(NotifyNewParcelHandler::class)
            ->arg('$roles', '%env(json:file:resolve:ROLES)%')

        ->set(ViewArchiveParcelsAction::class);
};
