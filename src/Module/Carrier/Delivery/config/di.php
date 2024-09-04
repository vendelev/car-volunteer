<?php

declare(strict_types=1);

use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\DeliveryPlayLoadFactory;
use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\UseCases\CreateDeliveryUseCase;
use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\UseCases\NotifyNewDeliveryUseCase;
use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\UseCases\SaveNewDeliveryUseCase;
use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\EntryPoint\BusHandler\NotifyNewDeliveryHandler;
use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\EntryPoint\BusHandler\SaveNewDeliveryHandler;
use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\EntryPoint\TelegramAction\CreateDeliveryAction;
use CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository\DeliveryRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(CreateDeliveryAction::class)
        ->set(DeliveryPlayLoadFactory::class)
        ->set(CreateDeliveryUseCase::class)
            ->arg('$volunteers', '%env(json:file:resolve:VOLUNTEERS)%')
        ->set(DeliveryRepository::class)

        ->set(SaveNewDeliveryHandler::class)
        ->set(SaveNewDeliveryUseCase::class)

        ->set(NotifyNewDeliveryHandler::class)
        ->set(NotifyNewDeliveryUseCase::class)
    ;
};