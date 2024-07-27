<?php

declare(strict_types=1);

namespace CarVolunteer\Infrastructure\Telegram;

use CarVolunteer\Domain\ActionRoute;

final readonly class ActionRoteResolver
{
    public function parseMessage(string $message): ?ActionRoute
    {
        if ($message[0] !== '/') {
            return null;
        }

        $urlInfo = parse_url($message);

        if (!isset($urlInfo['query'])) {
            return new ActionRoute($urlInfo['path']);
        }

        parse_str($urlInfo['query'], $query);

        return new ActionRoute($urlInfo['path'], $query);
    }
}