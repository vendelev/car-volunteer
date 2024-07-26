<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\User\EntryPoint\EntryPoint\BusHandler;

use CarVolunteer\Module\Telegram\Domain\RegisterUserCommand;
use CarVolunteer\Module\Telegram\User\Application\UseCase\RegisterUserUseCase;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class RegisterUserHandler
{
    public function __construct(
        private RegisterUserUseCase $useCase,
    ) {
    }

    #[Handler]
    public function handle(RegisterUserCommand $command): void
    {
        $this->useCase->handle($command->user);
    }
}