<?php

declare(strict_types=1);

namespace CarVolunteer\Component\Conversation\Infrastructure\Repository;

use CarVolunteer\Component\Conversation\Domain\ConversationRepositoryInterface;
use CarVolunteer\Component\Conversation\Domain\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 */
final class ConversationRepository extends ServiceEntityRepository implements ConversationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }
}
