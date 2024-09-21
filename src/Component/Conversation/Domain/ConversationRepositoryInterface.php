<?php

declare(strict_types=1);

namespace CarVolunteer\Component\Conversation\Domain;

use CarVolunteer\Component\Conversation\Domain\Entity\Conversation;
use Doctrine\Persistence\ObjectRepository;

/**
 * @extends ObjectRepository<Conversation>
 */
interface ConversationRepositoryInterface extends ObjectRepository
{
}
