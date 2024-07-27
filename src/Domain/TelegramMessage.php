<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

use CarVolunteer\Domain\Conversation\Conversation;

final readonly class TelegramMessage
{
    public function __construct(
        public string $userId,
        public ?string $message,
        public Conversation $conversation
    ) {
    }
}