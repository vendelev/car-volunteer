<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Domain;

use TelegramBot\Api\Types\Update;
use Telephantast\MessageBus\ContextAttribute;

final readonly class ReceiveMessageAttribute implements ContextAttribute
{
    public function __construct(public Update $telegramMessage)
    {
    }
}
