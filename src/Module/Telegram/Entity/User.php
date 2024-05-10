<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\Entity;

use CarVolunteer\Module\Telegram\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user', schema: 'telegram')]
final class User
{
    /**
     * @param string $id
     * @param string $username
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: Types::STRING)]
        public string $id,
        #[ORM\Column(type: Types::STRING, length: 255)]
        public string $username
    ) {
    }
}
