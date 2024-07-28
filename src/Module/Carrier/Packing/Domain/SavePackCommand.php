<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Domain;

use Telephantast\Message\Message;

final readonly class SavePackCommand implements Message
{
    public function __construct(
        public string       $userId,
        public PackPlayLoad $pack,
    ) {
    }
}