<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\Domain;

final class CallbackData
{
    public function __construct(
        public string $data,
    ) {
    }
}