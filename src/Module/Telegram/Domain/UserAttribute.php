<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\Domain;

use Telephantast\MessageBus\ContextAttribute;

final readonly class UserAttribute implements ContextAttribute
{
    public function __construct(public ?User $user)
    {
    }
}
