<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\EntryPoint\BusHandler;

use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Conversation\GetLastConversationQuery;
use CarVolunteer\Domain\Conversation\SaveConversationCommand;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Infrastructure\Telegram\ActionLocator;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\ReceiveMessageEvent;
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
            $this->logger->alert('Не найден пользователь в сообщении');
            return;
        }

        $message = $event->message;
        $callback = $event->callbackData;

        //todo переделать на middleware: получение и сохранение?
        /** @var Conversation|null $conversation */
        $conversation = $messageContext->dispatch(new GetLastConversationQuery($user->id));

        $this->logger->debug($message->text ?? '-');
        $this->logger->debug($callback->data ?? '-');

        $messageText = null;
        $action = $this->actionLocator->get($message->text ?? '-')
            ?? $this->actionLocator->get($callback->data ?? '-');

        if ($action === null) {
            $action = $this->actionLocator->get($conversation->actionRoute ?? '-');
            if ($action !== null && $callback === null) {
                $messageText = $message->text ?? null;
            }
        }

        if ($action === null) {
            $messageContext->dispatch(new SendMessageCommand(
                $user->id,
                'Потерял нить сообщений, нажмите кнопку "Помощь"',
                new InlineKeyboardMarkup([
                    [['text' => 'Помощь', 'callback_data' => '/help']],
                ])
            ));

            return;
        }

        $result = $action->handle(
            new TelegramMessage($user->id, $messageText, $conversation),
            $messageContext
        );

        $messageContext->dispatch(new SaveConversationCommand($user->id, $result));
    }
}