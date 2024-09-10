<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\FinishDelivery\Domain;

use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class FinishDeliveryPlayLoad
{
    public function __construct(
        public Uuid $parcelId
    ) {
    }
}
