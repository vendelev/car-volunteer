<?php

declare(strict_types=1);

namespace CarVolunteer\Infrastructure\Telegram;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\User\UserRole;

final readonly class ActionInfo
{
    public function __construct(
        /** @var class-string<ActionInterface> */
        public string $className,
        public string $defaultTitle,
        public ActionRouteMap $route,
        /** @var list<UserRole> */
        public array $accessRoles = [],
    ) {
    }
}
