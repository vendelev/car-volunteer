<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\SendMessage\EntryPoint\BusHandler;

use CarVolunteer\Domain\Telegram\SendPhotoCommand;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;
use Psr\Log\LoggerInterface;
use TelegramBot\Api\BotApi;
use Throwable;

/**
 * Отправка фото в телеграм
 */
final readonly class SendPhotoHandler
{
    public function __construct(
        private BotApi $api,
        private LoggerInterface $logger
    ) {
    }

    #[Handler]
    public function sendMessage(SendPhotoCommand $command): void
    {
        try {
            $this->api->sendPhoto(
                $command->chatId,
                $command->photoId,
                $command->text,
                $command->replyToMessageId,
                $command->replyMarkup,
                $command->disableNotification,
                $command->parseMode,
                $command->messageThreadId,
                $command->protectContent,
                $command->allowSendingWithoutReply
            );
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
