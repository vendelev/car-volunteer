<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Application;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;
use Throwable;

/**
 * Получение из реквеста Телеграм сообщения
 */
final readonly class IncomeMessageParser
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function parse(Request $request): ?Update
    {
        $content = $request->getContent();
        $this->logger->debug($content);

        if ($content) {
            try {
                /** @var array<mixed> $data */
                $data = BotApi::jsonValidate($content, true);
                return Update::fromResponse($data);
            } catch (Throwable $exception) {
                $this->logger->alert($exception->getMessage());
            }
        }

        return null;
    }
}