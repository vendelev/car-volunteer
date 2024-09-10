<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\Domain;

use Telephantast\Message\Message;

final readonly class ParcelFilledEvent implements Message
{
    public function __construct(
        public string $userId,
        public ParcelPlayLoad $parcel,
    ) {
    }
}
