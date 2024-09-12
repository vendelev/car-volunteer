<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Fake;

use Telephantast\Message\Message;

/**
 * @psalm-immutable
 * @implements Message<void>
 */
final readonly class TestMessage implements Message
{
}
