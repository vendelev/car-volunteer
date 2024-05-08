<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

final readonly class TelegramUser
{
    public function __construct(
        public string $id,
        public string $username,
    ) {
    }
}
