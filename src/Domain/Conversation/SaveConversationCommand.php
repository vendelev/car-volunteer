<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\Conversation;

use Telephantast\Message\Message;

final readonly class SaveConversationCommand implements Message
{
    public function __construct(
        public string $userId,
        public Conversation $conversation,
    ) {
    }
}
