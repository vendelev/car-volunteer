<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\DeleteParcel\Domain;

use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class DeletePlayLoad
{
    public function __construct(
        public Uuid $id,
        public bool $confirm
    ) {
    }
}
