<?php

declare(strict_types=1);

namespace CarVolunteer\Component\Conversation\EntryPoint\BusHandler;

use CarVolunteer\Component\Conversation\Application\UseCases\SaveConversation;
use CarVolunteer\Domain\Conversation\SaveConversationCommand;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class SaveConversationHandler
{
    public function __construct(
        private SaveConversation $useCase,
    ) {
    }
    
    #[Handler]
    public function handle(SaveConversationCommand $command): void
    {
        $this->useCase->handle($command->userId, $command->conversation);
    }
}