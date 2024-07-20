<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\User\Auth\Middleware;

use Telephantast\MessageBus\Handler\Pipeline;
use Telephantast\MessageBus\MessageContext;
use Telephantast\MessageBus\Middleware;


final class AuthenticateMiddleware implements Middleware
{
    public function handle(MessageContext $messageContext, Pipeline $pipeline): mixed
    {
        return $pipeline->continue();
    }
}