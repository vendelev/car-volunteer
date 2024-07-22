<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Domain;

final class CallbackData
{
    public function __construct(
        public string $data,
    ) {
    }
}