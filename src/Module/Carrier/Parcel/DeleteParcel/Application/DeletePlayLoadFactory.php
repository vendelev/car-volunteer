<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\DeleteParcel\Application;

use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Module\Carrier\Parcel\DeleteParcel\Domain\DeletePlayLoad;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class DeletePlayLoadFactory
{
    public function createFromConversation(Conversation $conversation): DeletePlayLoad
    {
        $playLoad = $conversation->playLoad ?? ['id' => null];

        $queryId = $conversation->actionRoute->query['id'] ?? null;
        $confirm = filter_var($conversation->actionRoute->query['confirm'] ?? '', FILTER_VALIDATE_BOOL);

        if ($queryId !== null && $queryId !== $playLoad['id']) {
            $playLoad['id'] = $queryId;
            $confirm = false;
        }

        if ($playLoad['id'] === null) {
            return new DeletePlayLoad(
                id: $queryId ? Uuid::fromString((string)$queryId) : Uuid::nil(),
                confirm: false
            );
        }

        return new DeletePlayLoad(
            id: Uuid::fromString((string)$playLoad['id']),
            confirm: $confirm
        );
    }
}
