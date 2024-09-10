<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\SendMessage\EntryPoint\BusHandler;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;
use Psr\Log\LoggerInterface;
use TelegramBot\Api\BotApi;
use Throwable;

/**
 * Отправка сообщения в телеграм
 */
final readonly class SendMessageHandler
{
    public function __construct(
        private BotApi $api,
        private LoggerInterface $logger
    ) {
    }

    #[Handler]
    public function sendMessage(SendMessageCommand $command): void
    {
        try {
            $this->api->sendMessage(
                $command->chatId,
                $command->text,
                $command->parseMode,
                $command->disablePreview,
                $command->replyToMessageId,
                $command->replyMarkup,
                $command->disableNotification,
                $command->messageThreadId,
                $command->protectContent,
                $command->allowSendingWithoutReply,
            );
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
