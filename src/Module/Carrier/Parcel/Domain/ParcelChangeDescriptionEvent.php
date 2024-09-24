<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\Domain;

use Telephantast\Message\Event;

final readonly class ParcelChangeDescriptionEvent implements Event
{
    public function __construct(
        public Parcel $parcel,
    ) {
    }
}
