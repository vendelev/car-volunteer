<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\Web;

use CarVolunteer\Module\Telegram\MessageReceived\Application\CommandLocator;
use CarVolunteer\Module\Telegram\MessageReceived\Domain\TelegramMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

final readonly class WebhookController
{
    public function __construct(private CommandLocator $commandLocator)
    {
    }

    #[Route('/', name: 'telegram_webhook')]
    public function index(Request $request, LoggerInterface $logger): Response
    {
        $content = $request->getContent();
        $logger->debug($content);

        if ($content) {
            /** @var array<mixed> $data */
            $data = BotApi::jsonValidate($content, true);
            $update = Update::fromResponse($data);
            $message = TelegramMessage::fromTelegram($update);

            if ($message && $this->commandLocator->has($message->command)) {
                $this->commandLocator->get($message->command)?->handle($message);
            }
        }

        return new Response();
    }
}
