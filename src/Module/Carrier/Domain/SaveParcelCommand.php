<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Domain;

use CarVolunteer\Module\Carrier\Domain\Dto\Parcel;
use Telephantast\Message\Message;

final readonly class SaveParcelCommand implements Message
{
    public function __construct(
        public string $userId,
        public Parcel $parcel,
    ) {
    }

}