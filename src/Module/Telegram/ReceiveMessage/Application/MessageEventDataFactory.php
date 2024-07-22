<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Application;

use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\CallbackData;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\Message;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\User;
use TelegramBot\Api\Types\Update;

final class MessageEventDataFactory
{
    public function getUser(Update $telegramMessage): ?User
    {
        $fromUser = $telegramMessage->getMessage()?->getFrom();
        if ($fromUser === null) {
            $fromUser = $telegramMessage->getCallbackQuery()?->getFrom();
        }

        if ($fromUser !== null) {
            return new User(
                id: (string)$fromUser->getId(),
                username: (string)$fromUser->getUsername()
            );
        }

        return null;
    }

    public function getMessage(Update $telegramMessage): ?Message
    {
        $message = $telegramMessage->getMessage();

        if ($message === null) {
            $message = $telegramMessage->getCallbackQuery()?->getMessage();
        }

        if ($message !== null) {
            return new Message(
                messageId: (string)$message->getMessageId(),
                text: (string)$message->getText(),
            );
        }

        return null;
    }

    public function getCallback(Update $telegramMessage): ?CallbackData
    {
        $callBackQuery = $telegramMessage->getCallbackQuery();
        if ($callBackQuery) {
            return new CallbackData((string)$callBackQuery->getData());
        }

        return null;
    }
}