<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use Telephantast\Message\Message;
use Telephantast\MessageBus\MessageContext;

interface ActionInterface
{
    public static function getInfo(): ActionInfo;

    /**
     * @template TResult
     * @template TMessage of Message<TResult>
     * @param MessageContext<TResult, TMessage> $messageContext
     */
    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation;
}
