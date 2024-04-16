<?php

declare(strict_types=1);

namespace CarVolunteer\Infrastructure\Symfony\HttpKernel;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $di, ContainerBuilder $containerBuilder): void {
    $containerBuilder->addCompilerPass(new class () implements CompilerPassInterface {
        public function process(ContainerBuilder $container): void
        {
            $container->removeDefinition('locale_listener');
        }
    });
};
