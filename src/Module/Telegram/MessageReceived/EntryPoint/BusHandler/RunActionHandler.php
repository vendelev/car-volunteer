<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\BusHandler;

use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Conversation\GetLastConversationQuery;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Infrastructure\Telegram\ActionLocator;
use CarVolunteer\Module\Telegram\MessageReceived\Domain\ReceiveMessageEvent;
use CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\TelegramAction\HelpAction;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;
use Psr\Log\LoggerInterface;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class RunActionHandler
{
    public function __construct(
        private LoggerInterface $logger,
        private ActionLocator $actionLocator,
    ) {
    }

    #[Handler]
    public function receiveMessage(ReceiveMessageEvent $event, MessageContext $messageContext): void
    {
        $user = $event->user;

        if ($user === null) {
            $this->logger->alert('Не найден пользователь');
            return;
        }

        $message = $event->message;
        $callback = $event->callbackData;

        /** @var Conversation $conversation */
        $conversation = $messageContext->dispatch(new GetLastConversationQuery($user->id));

        $action = $this->actionLocator->get($message->text ?? '')
            ?? $this->actionLocator->get($callback->data ?? '')
            ?? $conversation->action;

        if ($action === null) {
            $messageContext->dispatch(new SendMessageCommand(
                $user->id,
                'Потерял нить сообщений, нажмите кнопку "Помощь"',
                new InlineKeyboardMarkup([
                    [['text' => 'Помощь', 'callback_data' => HelpAction::getActionName()]],
                ])
            ));

            return;
        }

        $action->handle(
            new TelegramMessage($user->id, $message->text ?? '', $conversation),
            $messageContext
        );
    }
}