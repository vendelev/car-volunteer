<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Domain;

use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\Message\Message;

/**
 * @implements Message<bool>
 */
final readonly class CheckParcelDeliveredQuery implements Message
{
    public function __construct(
        public Uuid $parcelId
    ) {
    }
}