<?php

declare(strict_types=1);

namespace CarVolunteer\Component\AccessRights\Application;

use CarVolunteer\Domain\User\UserRole;

final readonly class RightsChecker
{
    /**
     * @param list<UserRole> $roles
     */
    public function canEdit(string $authorId, string $userId, array $roles): bool
    {
        return (
            $authorId === $userId
            || in_array(UserRole::Manager, $roles, true)
            || in_array(UserRole::Admin, $roles, true)
        );
    }
}
