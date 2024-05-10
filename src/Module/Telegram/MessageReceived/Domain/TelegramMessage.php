<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\Domain;

use CarVolunteer\Domain\TelegramUser;
use TelegramBot\Api\Types\Update;

final class TelegramMessage
{
    public function __construct(
        public string $id,
        public string $command,
        public TelegramUser $user,
    ) {
    }

    public static function fromTelegram(Update $income): ?self
    {
        $message = $income->getMessage();
        if ($message !== null) {
            $user = new TelegramUser(
                id: (string)$message->getFrom()?->getId(),
                username: (string)$message->getFrom()?->getUsername()
            );
            return new self(
                id: (string)$message->getMessageId(),
                command: (string)$message->getText(),
                user: $user,
            );
        }

        $callback = $income->getCallbackQuery();
        if ($callback !== null) {
            $user = new TelegramUser(
                id: (string)$callback->getFrom()->getId(),
                username: (string)$callback->getFrom()->getUsername()
            );
            return new self(
                id: (string)$callback->getMessage()?->getMessageId(),
                command: (string)$callback->getData(),
                user: $user,
            );
        }

        return null;
    }
}
