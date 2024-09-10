<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\Domain;

final class User
{
    public function __construct(
        public string $id,
        public string $username,
        public string $firstName,
        public ?string $lastName,
    ) {
    }
}
