<?php

declare(strict_types=1);

namespace CarVolunteer\Component\Conversation\Domain\Entity;

use CarVolunteer\Component\Conversation\Infrastructure\Repository\ConversationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use HardcorePhp\Infrastructure\Uuid\DoctrineDBAL\UuidType;
use HardcorePhp\Infrastructure\Uuid\Uuid;

#[ORM\Table(name: 'conversation', schema: 'conversation')]
#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ORM\Index(name: 'last_conversation', columns: ['user_id', 'id'])]
class Conversation
{
    public function __construct(
        #[ORM\Id, ORM\Column(type: UuidType::class)]
        public Uuid $id,
        #[ORM\Column(type: Types::STRING, length: 20)]
        public string $userId,
        #[ORM\Column(type: Types::JSON)]
        public array $actionRoute,
        #[ORM\Column(type: Types::JSON, nullable: true)]
        public ?array $playLoad,
    ) {
    }
}