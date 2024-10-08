<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Domain;

use CarVolunteer\Module\Telegram\Domain\User;
use Telephantast\Message\Event;

final readonly class ReceiveMessageEvent implements Event
{
    public function __construct(
        public ?User $user,
        public ?Message $message,
        public ?CallbackQuery $callbackQuery,
    ) {
    }
}
