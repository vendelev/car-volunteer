<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\Domain;

use Telephantast\Message\Event;

final readonly class ParcelCreatedEvent implements Event
{
    public function __construct(
        public string $userId,
        public ParcelPlayLoad $parcel,
    ) {
    }
}
