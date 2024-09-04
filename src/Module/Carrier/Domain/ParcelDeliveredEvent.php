<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Domain;

use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\Message\Event;

final readonly class ParcelDeliveredEvent implements Event
{
    public function __construct(
        public Uuid $parcelId,
        public Uuid $deliveryId,
    ) {
    }
}