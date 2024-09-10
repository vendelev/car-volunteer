<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Domain;

use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use DateTimeImmutable;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final class CreateDeliveryPlayLoad
{
    public function __construct(
        public readonly Uuid $id,
        public readonly Uuid $parcelId,
        public DeliveryStatus $status,
        public ?DateTimeImmutable $deliveryDate = null,
        public ?string $carrierId = null
    ) {
    }
}
