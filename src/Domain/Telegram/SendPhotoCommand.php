<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\Telegram;

use TelegramBot\Api\Types\ForceReply;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardRemove;
use Telephantast\Message\Message;

/**
 * @implements Message<self>
 */
final class SendPhotoCommand implements Message
{
    public function __construct(
        public int|string $chatId,
        public string $photoId,
        public ?string $text = null,
        public InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $replyMarkup = null,
        public string|null $parseMode = 'html',
        public int|null $replyToMessageId = null,
        public bool $disableNotification = false,
        public int|null $messageThreadId = null,
        public bool|null $protectContent = null,
        public bool|null $allowSendingWithoutReply = null
    ) {
    }
}
