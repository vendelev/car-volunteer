<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\Domain;

use Telephantast\Message\Message;

final readonly class RegisterUserCommand implements Message
{
    public function __construct(
        public User $user
    ) {
    }
}