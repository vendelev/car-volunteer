<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\Photo;

use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\Message\Message;

/**
 * @implements Message<list<string>>
 */
final readonly class GetAllPhotoQuery implements Message
{
    public function __construct(
        public Uuid $objectId
    ) {
    }
}
