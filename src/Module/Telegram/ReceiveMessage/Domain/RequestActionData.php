<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\Domain;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\ActionRoute;

final readonly class RequestActionData
{
    public function __construct(
        public ?ActionInterface $actionHandler,
        public ?ActionRoute $actionRoute,
        public ?string $messageText
    ) {
    }
}
