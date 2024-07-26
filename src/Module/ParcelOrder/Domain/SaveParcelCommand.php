<?php

declare(strict_types=1);

namespace CarVolunteer\Module\ParcelOrder\Domain;

use CarVolunteer\Module\ParcelOrder\Domain\Dto\Parcel;
use Telephantast\Message\Message;

final readonly class SaveParcelCommand implements Message
{
    public function __construct(
        public string $userId,
        public Parcel $parcel,
    ) {
    }

}