<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\Conversation;

final readonly class Conversation
{
    public function __construct(
        public ?string $action
    ) {
    }
}