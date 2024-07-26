<?php

declare(strict_types=1);

namespace CarVolunteer\Component\Conversation\Application\UseCases;

use CarVolunteer\Component\Conversation\Domain\Entity\Conversation;
use CarVolunteer\Domain\Conversation\Conversation as ConversationDTO;
use Doctrine\ORM\EntityManagerInterface;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class SaveConversation
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(string $userId, ConversationDTO $conversation): void
    {
        $entity = new Conversation(
            Uuid::v7(),
            $userId,
            $conversation->actionRoute,
            $conversation->playLoad
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}