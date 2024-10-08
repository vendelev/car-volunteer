<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Domain;

final class Message
{
    public function __construct(
        public string $messageId,
        public string $text,
        public ?string $photoId
    ) {
    }
}
