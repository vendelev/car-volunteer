<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\Application;

use CarVolunteer\Domain\TelegramUser;
use CarVolunteer\Module\Telegram\MessageReceived\Domain\UserRole;

final readonly class Authorization
{
    /**
     * @param array{manager: list<string>, picker: list<string>, volunteer: list<string>} $roles
     */
    public function __construct(
        private array $roles,
    ) {
    }

    public function isManager(TelegramUser $user): bool
    {
        return in_array($user->id, $this->roles[UserRole::Manager->value], true);
    }

    public function isPicker(TelegramUser $user): bool
    {
        return in_array($user->id, $this->roles[UserRole::Picker->value], true);
    }

    public function isVolunteer(TelegramUser $user): bool
    {
        return in_array($user->id, $this->roles[UserRole::Volunteer->value], true);
    }
}
