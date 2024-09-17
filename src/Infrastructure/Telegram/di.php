<?php

declare(strict_types=1);

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Infrastructure\Telegram\ActionLocator;
use CarVolunteer\Infrastructure\Telegram\ActionRoteResolver;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator, ContainerBuilder $builder): void {
    $builder->registerForAutoconfiguration(ActionInterface::class)->addTag(ActionInterface::class);

    $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->set(ActionLocator::class)
        ->set(ActionRoteResolver::class)
        ->set(ButtonResponder::class)
        ->set(ActionRouteAccess::class);
};
