<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Application;

use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\ReceiveMessageAttribute;
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
        $context = $this->messageBus->startContext(new ReceiveMessageEvent(
            user: $this->dataFactory->getUser($incomeMessage),
            message: $this->dataFactory->getMessage($incomeMessage),
            callbackData: $this->dataFactory->getCallback($incomeMessage),
        ));
        $context->setAttribute(new ReceiveMessageAttribute($incomeMessage));

        return $context;
    }
}