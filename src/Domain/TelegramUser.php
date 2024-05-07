<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

final readonly class TelegramUser
{
    public function __construct(
        public int $id,
        public string $username,
    ) {
    }
}
