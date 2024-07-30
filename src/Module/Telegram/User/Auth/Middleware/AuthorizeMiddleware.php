<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\User\Auth\Middleware;

use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\ReceiveMessageEvent;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\MessageBusMiddleware;
use Telephantast\MessageBus\Handler\Pipeline;
use Telephantast\MessageBus\MessageContext;
use Telephantast\MessageBus\Middleware;

#[MessageBusMiddleware]
final readonly class AuthorizeMiddleware implements Middleware
{
    /**
     * @param array{Admin: list<string>, Manager: list<string>, Picker: list<string>, Volunteer: list<string>} $roles
     */
    public function __construct(
        private array $roles,
    ) {
    }

    public function handle(MessageContext $messageContext, Pipeline $pipeline): mixed
    {
        if ($messageContext->getAttribute(AuthorizeAttribute::class)) {
            return $pipeline->continue();
        }

        /** @var ReceiveMessageEvent $event */
        $event = $messageContext->getMessage();
        $user = $event->user;

        if ($user) {
            $roles = [];
            foreach ($this->roles as $role => $userIds) {
                if (in_array($user->id, $userIds, true)) {
                    $roles[] = UserRole::{$role};
                }
            }

            $messageContext->setAttribute(new AuthorizeAttribute($user->id, $roles));
        }

        return $pipeline->continue();
    }
}