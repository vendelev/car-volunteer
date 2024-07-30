<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\User\Entity;

use CarVolunteer\Module\Telegram\User\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user', schema: 'telegram')]
final class User
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: Types::STRING)]
        public string $id,
        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        public ?string $username = null,
        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        public ?string $firstName = null,
        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        public ?string $lastName = null,
    ) {
    }
}
