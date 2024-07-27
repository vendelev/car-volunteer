<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\Conversation;

use CarVolunteer\Domain\ActionRoute;

final readonly class Conversation
{
    public function __construct(
        public ActionRoute $actionRoute,
        public ?array $playLoad = null,
    ) {
    }
}