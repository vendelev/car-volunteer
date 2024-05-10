<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\Application;

use CarVolunteer\Module\Telegram\MessageReceived\Domain\CommandHandler;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;

final class CommandLocator implements ContainerInterface
{
    /** @var array<string, class-string<\CarVolunteer\Module\Telegram\MessageReceived\Domain\CommandHandler>> */
    private array $routes;

    /**
     * @param ServiceLocator<CommandHandler> $locator
     */
    public function __construct(
        #[AutowireLocator(CommandHandler::class)]
        private readonly ServiceLocator $locator,
    ) {
        /** @var class-string<\CarVolunteer\Module\Telegram\MessageReceived\Domain\CommandHandler> $service */
        foreach ($locator->getProvidedServices() as $service) {
            $this->routes[$service::getCommandName()] = $service;
        }
    }

    public function get(string $id): ?CommandHandler
    {
        return $this->has($id) ? $this->locator->get($this->routes[$id]) : null;
    }

    public function has(string $id): bool
    {
        return isset($this->routes[$id]);
    }
}
