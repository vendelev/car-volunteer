<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\EntryPoint\BusHandler;

use CarVolunteer\Domain\ActionRoute;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Conversation\GetLastConversationQuery;
use CarVolunteer\Domain\Conversation\SaveConversationCommand;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Telegram\ReceiveMessage\Application\UseCase\GetRequestActionDataUseCase;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\ReceiveMessageEvent;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;
use Psr\Log\LoggerInterface;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;
use Throwable;

final readonly class RunActionHandler
{
    public function __construct(
        private LoggerInterface $logger,
        private GetRequestActionDataUseCase $getRequestActionData,
    ) {
    }

    #[Handler]
    public function receiveMessage(ReceiveMessageEvent $event, MessageContext $messageContext): void
    {
        $user = $event->user;

        if ($user === null) {
            $this->logger->alert('Не найден пользователь в сообщении');
            return;
        }

        //подумать и переделать на middleware: получение и сохранение?
        /** @var Conversation|null $conversation */
        $conversation = $messageContext->dispatch(new GetLastConversationQuery($user->id));
        $requestAction = $this->getRequestActionData->handle(
            $event->message,
            $event->callbackQuery,
            $conversation->actionRoute ?? null
        );

        if ($requestAction->actionHandler === null || $requestAction->actionRoute === null) {
            $messageContext->dispatch(new SendMessageCommand(
                $user->id,
                'Потерял нить сообщений, нажмите кнопку "Помощь"',
                new InlineKeyboardMarkup([
                    [['text' => 'В начало', 'callback_data' => ActionRouteMap::RootHelp->value]],
                ])
            ));

            return;
        }

        if (
            $conversation === null
            || $conversation->actionRoute->route !== $requestAction->actionRoute->route
        ) {
            $playLoad = null;
        } else {
            $playLoad = $conversation->playLoad;
        }

        $telegramMessage = new TelegramMessage(
            $user->id,
            $requestAction->messageText,
            $event->message->photoId,
            new Conversation(
                new ActionRoute(
                    $requestAction->actionRoute->route,
                    $requestAction->actionRoute->query
                ),
                $playLoad
            )
        );
        try {
            $result = $requestAction->actionHandler->handle($telegramMessage, $messageContext);
            $messageContext->dispatch(new SaveConversationCommand($user->id, $result));
        } catch (Throwable $exception) {
            $this->logger->critical($exception->getMessage(), ['trace' => $exception->getTrace()]);
        }
    }
}
