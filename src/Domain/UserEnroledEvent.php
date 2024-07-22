<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

final class UserEnroledEvent
{
    public function __construct(
        public TelegramUser $user
    ) {
    }
}