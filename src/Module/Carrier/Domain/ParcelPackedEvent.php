<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Domain;

use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\Message\Event;

final readonly class ParcelPackedEvent implements Event
{
    public function __construct(
        public string $pickerId,
        public Uuid $parcelId,
        public Uuid $packingId,
        public ?string $photoId,
    ) {
    }
}
