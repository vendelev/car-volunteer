<?php

declare(strict_types=1);

namespace CarVolunteer\Component\Conversation\EntryPoint\BusHandler;

use CarVolunteer\Component\Conversation\Application\UseCases\GetLastConversation;
use CarVolunteer\Domain\Conversation\GetLastConversationQuery;
use CarVolunteer\Domain\Conversation\Conversation;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;

final readonly class GetLastConversationHandler
{
    public function __construct(
        private GetLastConversation $useCase,
    ) {
    }

    #[Handler]
    public function handleQuery(GetLastConversationQuery $query): Conversation
    {
        return $this->useCase->handle($query->userId);
    }
}