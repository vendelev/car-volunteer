<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

use TelegramBot\Api\Types\Update;

final readonly class TelegramMessage
{
    public function __construct(
        public int $id,
        public string $command,
        public TelegramUser $user,
    ) {
    }

    public static function fromTelegram(Update $income): ?self
    {
        $message = $income->getMessage();
        if ($message !== null) {
            $user = new TelegramUser((int)$message->getFrom()?->getId(), (string)$message->getFrom()?->getUsername());
            return new self(
                (int)$message->getMessageId(),
                (string)$message->getText(),
                $user,
            );
        }

        $callback = $income->getCallbackQuery();
        if ($callback !== null) {
            $user = new TelegramUser($callback->getFrom()->getId(), $callback->getFrom()->getUsername());
            return new self(
                $callback->getMessage()->getMessageId(),
                $callback->getData(),
                $user,
            );
        }

        return null;
    }
}
