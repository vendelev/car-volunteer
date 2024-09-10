<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\User\EntryPoint\EntryPoint\BusHandler;

use CarVolunteer\Module\Telegram\Domain\UserJoinedEvent;
use CarVolunteer\Module\Telegram\User\Application\UseCase\SyncUserUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class SyncUserHandler
{
    public function __construct(
        private SyncUserUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(UserJoinedEvent $event): void
    {
        $this->useCase->handle($event->user);
    }
}
