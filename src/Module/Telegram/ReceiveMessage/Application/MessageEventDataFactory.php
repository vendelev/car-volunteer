<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Application;

use CarVolunteer\Module\Telegram\Domain\User;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\CallbackQuery;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\Message;
use TelegramBot\Api\Types\PhotoSize;
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
                username: (string)$fromUser->getUsername(),
                firstName: $fromUser->getFirstName(),
                lastName: $fromUser->getLastName(),
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
            /** @var list<PhotoSize>|null $photoData */
            $photoData = $message->getPhoto();
            $photo = is_array($photoData) ? array_pop($photoData) : null;

            return new Message(
                messageId: (string)$message->getMessageId(),
                text: (string)($message->getText() ?? $message->getCaption()),
                photoId: $photo?->getFileId()
            );
        }

        return null;
    }

    public function getCallback(Update $telegramMessage): ?CallbackQuery
    {
        $callBackQuery = $telegramMessage->getCallbackQuery();
        if ($callBackQuery) {
            return new CallbackQuery($callBackQuery->getId(), (string)$callBackQuery->getData());
        }

        return null;
    }
}
