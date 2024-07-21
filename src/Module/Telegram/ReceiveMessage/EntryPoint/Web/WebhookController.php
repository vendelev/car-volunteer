<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\EntryPoint\Web;

use CarVolunteer\Module\Telegram\ReceiveMessage\Application\IncomeMessageParser;
use CarVolunteer\Module\Telegram\ReceiveMessage\Application\ReceiveMessageEventFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Telephantast\MessageBus\MessageBus;

final readonly class WebhookController
{
    public function __construct(
        private MessageBus                   $messageBus,
        private IncomeMessageParser          $getMessage,
        private ReceiveMessageEventFactory $getContext
    ) {
    }

    #[Route('/', name: 'telegram_webhook')]
    public function index(Request $request): Response
    {
        $incomeMessage = $this->getMessage->parse($request);

        if ($incomeMessage) {
            /** @uses RunActionHandler::receiveMessage() */
            $this->messageBus->handleContext(
                $this->getContext->createContext($incomeMessage)
            );
        }

        return new Response();
    }
}
