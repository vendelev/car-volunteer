<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\Photo;

use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\Message\Message;

/**
 * @implements Message<void>
 */
final readonly class SaveNewPhotoCommand implements Message
{
    public function __construct(
        public string $photoId,
        public Uuid $objectId
    ) {
    }
}
