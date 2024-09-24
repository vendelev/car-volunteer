<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\EditParcel\Domain;

use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class EditParcelPlayLoad
{
    public function __construct(
        public Uuid $id,
        public ?string $text,
    ) {
    }
}
