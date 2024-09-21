<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\Domain;

use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\Message\Event;

final readonly class ParcelDeletedEvent implements Event
{
    public function __construct(
        public Uuid $parcelId,
    ) {
    }
}
