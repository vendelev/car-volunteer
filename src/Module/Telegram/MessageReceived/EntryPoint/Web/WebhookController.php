<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\Web;

use CarVolunteer\Module\Telegram\MessageReceived\Application\UseCases\ParseIncomeMessage;
use CarVolunteer\Module\Telegram\MessageReceived\Application\UseCases\CreateReceiveMessageContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Telephantast\MessageBus\MessageBus;

final readonly class WebhookController
{
    public function __construct(
        private MessageBus                  $messageBus,
        private ParseIncomeMessage          $getMessage,
        private CreateReceiveMessageContext $getContext
    ) {
    }

    #[Route('/', name: 'telegram_webhook')]
    public function index(Request $request): Response
    {
        $incomeMessage = $this->getMessage->handle($request);

        if ($incomeMessage) {
            /** @uses RunActionHandler::receiveMessage() */
            $this->messageBus->handleContext(
                $this->getContext->createContext($incomeMessage)
            );
        }

        return new Response();
    }
}
