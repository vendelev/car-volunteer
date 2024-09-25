<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Domain;

use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\Message\Event;

final readonly class DeliveryDeletedEvent implements Event
{
    public function __construct(
        public Uuid $deliveryId,
        public Uuid $parcelId
    ) {
    }
}
