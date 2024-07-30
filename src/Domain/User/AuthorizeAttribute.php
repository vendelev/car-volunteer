<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\User;

use Telephantast\MessageBus\InheritableContextAttribute;

final readonly class AuthorizeAttribute implements InheritableContextAttribute
{
    /**
     * @param list<UserRole> $roles
     */
    public function __construct(
        public string $userId,
        public array $roles
    ) {
    }
}