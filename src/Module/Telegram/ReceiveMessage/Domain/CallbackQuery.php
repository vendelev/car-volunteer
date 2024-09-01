<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Domain;

final class CallbackQuery
{
    public function __construct(
        public string $id,
        public string $data,
    ) {
    }
}