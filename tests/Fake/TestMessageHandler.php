<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Fake;

use Telephantast\Message\Message;
use Telephantast\MessageBus\Handler;
use Telephantast\MessageBus\HandlerRegistry\ArrayHandlerRegistry;
use Telephantast\MessageBus\MessageBus;
use Telephantast\MessageBus\MessageContext;

/**
 * @template TResult
 * @template TMessage of Message<TResult>
 * @implements Handler<null, TMessage>
 */
final class TestMessageHandler implements Handler
{
    /** @var list<TMessage> */
    public array $messages = [];

    public function id(): string
    {
        return 'test';
    }

    public function handle(MessageContext $messageContext): mixed
    {
        $this->messages[] = $messageContext->getMessage();

        return null;
    }

    /**
     * @param array<class-string<TMessage>, Handler<null, TMessage>> $handlers
     * @return MessageContext<TestMessage, TestMessage>
     */
    public function createMessageContext(array $handlers): MessageContext
    {
        return (new MessageBus(
            new ArrayHandlerRegistry($handlers)
        ))->startContext(new TestMessage());
    }
}
