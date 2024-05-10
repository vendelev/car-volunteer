<?php

declare(strict_types=1);

namespace CarVolunteer\Infrastructure\Symfony\HttpKernel;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $container): void {
    $container->addCompilerPass(new class () implements CompilerPassInterface {
        public function process(ContainerBuilder $container): void
        {
            $container->removeDefinition('locale_listener');
        }
    });
};
