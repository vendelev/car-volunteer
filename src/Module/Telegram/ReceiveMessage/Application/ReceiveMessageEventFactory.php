<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Application;

use CarVolunteer\Module\Telegram\Domain\UserAttribute;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\ReceiveMessageEvent;
use TelegramBot\Api\Types\Update;
use Telephantast\MessageBus\MessageBus;
use Telephantast\MessageBus\MessageContext;

final readonly class ReceiveMessageEventFactory
{
    public function __construct(
        private MessageBus $messageBus,
        private MessageEventDataFactory $dataFactory
    ) {
    }

    public function createContext(Update $incomeMessage): MessageContext
    {
        $user = $this->dataFactory->getUser($incomeMessage);
        $context = $this->messageBus->startContext(new ReceiveMessageEvent(
            user: $user,
            message: $this->dataFactory->getMessage($incomeMessage),
            callbackQuery: $this->dataFactory->getCallback($incomeMessage),
        ));
        $context->setAttribute(new UserAttribute($user));

        return $context;
    }
}
