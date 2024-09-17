<?php

declare(strict_types=1);

namespace CarVolunteer\Infrastructure\Telegram;

use CarVolunteer\Domain\ActionInterface;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * Сервис поиска телеграм комадн
 */
final class ActionLocator implements ContainerInterface
{
    /** @var array<string, class-string<ActionInterface>> */
    private array $routes;

    /**
     * @param ServiceLocator<ActionInterface> $locator
     * @throws RuntimeException
     */
    public function __construct(
        #[AutowireLocator(ActionInterface::class)]
        private readonly ServiceLocator $locator,
    ) {
        /** @var class-string<ActionInterface> $service */
        foreach ($locator->getProvidedServices() as $service) {
            $info = $service::getInfo();
            if (isset($this->routes[$info->route->value])) {
                throw new RuntimeException('Найдено 2-е одинаковых команды: ' . $info->route->value);
            }
            $this->routes[$info->route->value] = $info->className;
        }
    }

    public function get(string $id): ?ActionInterface
    {
        return $this->has($id) ? $this->locator->get($this->routes[$id]) : null;
    }

    public function has(string $id): bool
    {
        return isset($this->routes[$id]);
    }
}
