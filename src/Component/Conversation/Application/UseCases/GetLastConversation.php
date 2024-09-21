<?php

declare(strict_types=1);

namespace CarVolunteer\Component\Conversation\Application\UseCases;

use CarVolunteer\Component\Conversation\Domain\ConversationRepositoryInterface;
use CarVolunteer\Component\Conversation\Domain\Entity\Conversation;
use CarVolunteer\Domain\ActionRoute;
use CarVolunteer\Domain\Conversation\Conversation as ConversationDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class GetLastConversation
{
    public function __construct(
        private ConversationRepositoryInterface $repository,
        private DenormalizerInterface $denormalizer,
    ) {
    }

    public function handle(string $userId): ?ConversationDTO
    {
        $entity = $this->repository->findBy(['userId' => $userId], ['id' => 'desc'], 1)[0] ?? null;

        if ($entity === null) {
            return null;
        }

        return new ConversationDTO(
            $this->denormalizer->denormalize($entity->actionRoute, ActionRoute::class),
            $entity->playLoad
        );
    }
}
