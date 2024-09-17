<?php

declare(strict_types=1);

namespace CarVolunteer\Infrastructure\Telegram;

use CarVolunteer\Domain\User\UserRole;

final readonly class ActionRouteAccess
{
    public function __construct(
        private ActionLocator $actionLocator,
    ) {
    }

    /**
     * @param list<UserRole> $userRoles
     */
    public function get(ActionRouteMap $route, array $userRoles): ?ActionInfo
    {
        $action = $this->actionLocator->get($route->value);

        if ($action === null) {
            return null;
        }

        $info = $action::getInfo();

        if ($this->can($info->accessRoles, $userRoles)) {
            return $info;
        }

        return null;
    }

    /**
     * @param list<UserRole> $accessRoles
     * @param list<UserRole> $userRoles
     */
    public function can(array $accessRoles, array $userRoles): bool
    {
        if (count($accessRoles) === 0) {
            return true;
        }

        $accessRoles[] = UserRole::Admin;

        foreach ($accessRoles as $role) {
            if (in_array($role, $userRoles, true)) {
                return true;
            }
        }

        return false;
    }
}
