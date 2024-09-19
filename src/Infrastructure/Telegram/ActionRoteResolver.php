<?php

declare(strict_types=1);

namespace CarVolunteer\Infrastructure\Telegram;

use CarVolunteer\Domain\ActionRoute;

final readonly class ActionRoteResolver
{
    public function parseMessage(string $message): ?ActionRoute
    {
        if (!$message) {
            return null;
        }

        if ($message[0] !== '/') {
            return null;
        }

        $urlInfo = parse_url($message);

        if (isset($urlInfo['query'])) {
            parse_str($urlInfo['query'], $query);

            /** @var array<string, int|string>|null $query */
            return new ActionRoute($urlInfo['path'] ?? '', $query);
        }

        return new ActionRoute($urlInfo['path'] ?? '');
    }
}
