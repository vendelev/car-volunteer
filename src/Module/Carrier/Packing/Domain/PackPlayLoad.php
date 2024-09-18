<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Domain;

use HardcorePhp\Infrastructure\Uuid\Uuid;

final class PackPlayLoad
{
    public function __construct(
        public readonly Uuid $id,
        public readonly Uuid $parcelId,
        public PackStatus $status,
        public ?string $photoId = null,
    ) {
    }
}
