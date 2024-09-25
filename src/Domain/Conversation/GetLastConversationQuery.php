<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\Conversation;

use Telephantast\Message\Message;

/**
 * @implements Message<Conversation|null>
 */
final readonly class GetLastConversationQuery implements Message
{
    public function __construct(
        public string $userId
    ) {
    }
}
