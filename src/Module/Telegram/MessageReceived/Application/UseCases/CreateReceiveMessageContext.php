<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\Application\UseCases;

use CarVolunteer\Module\Telegram\MessageReceived\Application\MessageEventDataFactory;
use CarVolunteer\Module\Telegram\MessageReceived\Domain\ReceiveMessageAttribute;
use CarVolunteer\Module\Telegram\MessageReceived\Domain\ReceiveMessageEvent;
use TelegramBot\Api\Types\Update;
use Telephantast\MessageBus\MessageBus;
use Telephantast\MessageBus\MessageContext;

final readonly class CreateReceiveMessageContext
{
    public function __construct(
        private MessageBus $messageBus,
        private MessageEventDataFactory $factory
    ) {
    }

    public function createContext(Update $incomeMessage): MessageContext
    {
        $context = $this->messageBus->startContext(new ReceiveMessageEvent(
            user: $this->factory->getUser($incomeMessage),
            message: $this->factory->getMessage($incomeMessage),
            callbackData: $this->factory->getCallback($incomeMessage),
        ));
        $context->setAttribute(new ReceiveMessageAttribute($incomeMessage));

        return $context;
    }
}