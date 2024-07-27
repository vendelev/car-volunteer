<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Domain\Dto;

use CarVolunteer\Module\Carrier\Domain\ParcelStatus;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final class Parcel
{
    public function __construct(
        public readonly Uuid   $id,
        public ParcelStatus    $status,
        public ?string         $description = null,
        public ?string         $driverId = null,
        public ?string         $date = null,
    ) {
    }
}