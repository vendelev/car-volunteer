<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\Event;

use CarVolunteer\Domain\TelegramUser;

final class UserEnroled
{
    public function __construct(
        public TelegramUser $user
    ) {
    }
}