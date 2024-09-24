<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\EditParcel\Application;

use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Domain\EditParcelPlayLoad;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class EditParcelPlayLoadFactory
{
    public function createFromMessage(TelegramMessage $message): EditParcelPlayLoad
    {
        $parcelId = $message->conversation->actionRoute->query['id'] ?? '';

        return new EditParcelPlayLoad(
            id: $parcelId ? Uuid::fromString((string)$parcelId) : Uuid::nil(),
            text: $message->message
        );
    }
}
