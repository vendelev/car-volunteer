<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\EntryPoint\Web;

use CarVolunteer\Domain\TelegramMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

class WebhookController
{
    #[Route('/', name: 'telegram_webhook')]
    public function index(Request $request, MessageBusInterface $bus, LoggerInterface $logger): Response
    {
        $content = $request->getContent();
        $logger->debug($content);

        if ($content) {
            /** @var array<mixed> $data */
            $data = BotApi::jsonValidate($content, true);
            $update = Update::fromResponse($data);
            $message = TelegramMessage::fromTelegram($update);
            if ($message !== null) {
                $bus->dispatch($message);
            }
        }

        return new Response();
    }
}
