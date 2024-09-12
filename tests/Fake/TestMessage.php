<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Fake;

use Telephantast\Message\Message;

/**
 * @psalm-immutable
 * @implements Message<self>
 */
final readonly class TestMessage implements Message
{
}
