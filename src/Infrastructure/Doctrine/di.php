<?php

declare(strict_types=1);

namespace HardcorePhp\Infrastructure\Doctrine;

use Doctrine\ORM\Tools\ToolEvents;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $di): void {
    $di->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
        ->set(FixPostgreSQLDefaultSchemaListener::class)
            ->tag('doctrine.event_listener', ['event' => ToolEvents::postGenerateSchema]);
};
