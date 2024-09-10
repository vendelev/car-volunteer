<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

final readonly class ActionRoute
{
    public function __construct(
        public string $route,
        /** @var array<string, int|string>|null */
        public ?array $query = null,
    ) {
    }
}
