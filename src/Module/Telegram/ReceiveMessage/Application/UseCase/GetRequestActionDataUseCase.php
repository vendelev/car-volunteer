<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Application\UseCase;

use CarVolunteer\Domain\ActionRoute;
use CarVolunteer\Infrastructure\Telegram\ActionLocator;
use CarVolunteer\Infrastructure\Telegram\ActionRoteResolver;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\CallbackQuery;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\Message;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\RequestActionData;
use Psr\Log\LoggerInterface;

final readonly class GetRequestActionDataUseCase
{
    public function __construct(
        private ActionLocator $actionLocator,
        private ActionRoteResolver $roteResolver,
    ) {
    }

    public function handle(
        ?Message $message,
        ?CallbackQuery $callbackQuery,
        ?ActionRoute $conversationActionRoute
    ): RequestActionData {
        $request = $this->getRequest($message->text ?? '-');

        if ($request === null) {
            $request = $this->getRequest($callbackQuery->data ?? '-');
        }

        if ($request !== null) {
            return $request;
        }

        $actionHandler = null;
        $actionRoute = null;
        $messageText = null;

        if ($conversationActionRoute !== null) {
            $actionRoute = $conversationActionRoute;
            $actionHandler = $this->actionLocator->get($actionRoute->route);

            if ($actionHandler !== null) {
                $messageText = $callbackQuery->data ?? ($message->text ?? null);
            }
        }

        return new RequestActionData($actionHandler, $actionRoute, $messageText);
    }

    private function getRequest(string $text): ?RequestActionData
    {
        $actionRoute = $this->roteResolver->parseMessage($text);
        if ($actionRoute) {
            $actionHandler = $this->actionLocator->get($actionRoute->route);

            if ($actionHandler !== null) {
                return new RequestActionData($actionHandler, $actionRoute, null);
            }
        }

        return null;
    }
}
