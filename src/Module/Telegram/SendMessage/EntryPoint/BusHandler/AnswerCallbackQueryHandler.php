<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\SendMessage\EntryPoint\BusHandler;

use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\ReceiveMessageEvent;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;
use Psr\Log\LoggerInterface;
use TelegramBot\Api\BotApi;
use Throwable;

/**
 * Подтверждение клика кнопки в телеграм
 */
final readonly class AnswerCallbackQueryHandler
{
    public function __construct(
        private BotApi $api,
        private LoggerInterface $logger
    ) {
    }

    #[Handler]
    public function sendAnswer(ReceiveMessageEvent $event): void
    {
        if ($event->callbackQuery !== null) {
            try {
                $this->api->answerCallbackQuery($event->callbackQuery->id);
            } catch (Throwable $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
    }
}
