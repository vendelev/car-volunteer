<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\Domain;

use HardcorePhp\Infrastructure\Uuid\Uuid;

final class ParcelPlayLoad
{
    public function __construct(
        public readonly Uuid   $id,
        public ParcelStatus    $status,
        public ?string         $title = null,
        public ?string         $description = null,
    ) {
    }
}