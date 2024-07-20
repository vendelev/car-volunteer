<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\Conversation;

use Telephantast\Message\Message;

final readonly class StartNewConversationCommand implements Message
{
    public function __construct(
        public string $action
    ) {
    }
}