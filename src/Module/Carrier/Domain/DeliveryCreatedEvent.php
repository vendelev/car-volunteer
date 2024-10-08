<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Domain;

use DateTimeImmutable;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\Message\Event;

final readonly class DeliveryCreatedEvent implements Event
{
    public function __construct(
        public string $carrierId,
        public Uuid $parcelId,
        public Uuid $deliveryId,
        public DateTimeImmutable $deliveryDate,
    ) {
    }
}
