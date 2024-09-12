<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Fake;

use Telephantast\Message\Message;
use Telephantast\MessageBus\Handler;
use Telephantast\MessageBus\MessageContext;

/**
 * @template TResult
 * @template TMessage of Message<TResult>
 * @implements Handler<null, TMessage>
 */
final class TestMessageHandler implements Handler
{
    /** @var list<Message> */
    public array $messages = []; // @phpstan-ignore-line

    public function id(): string
    {
        return 'test';
    }

    public function handle(MessageContext $messageContext): mixed
    {
        $this->messages[] = $messageContext->getMessage();

        return null;
    }
}
