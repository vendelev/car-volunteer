<?php

declare(strict_types=1);

namespace CarVolunteer\Infrastructure\Telegram;

final readonly class RouteParam
{
    public function __construct(
        public string $name,
        public string $value,
    ) {
    }
}
