<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\Domain;

use Telephantast\Message\Event;

final readonly class UserJoinedEvent implements Event
{
    public function __construct(
        public User $user
    ) {
    }
}