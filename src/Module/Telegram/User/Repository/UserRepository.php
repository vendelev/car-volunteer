<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\User\Repository;

use CarVolunteer\Domain\TelegramUser;
use CarVolunteer\Module\Telegram\User\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
final class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(TelegramUser $user): void
    {
        $dbUser = new User($user->id, $user->username);

        $this->getEntityManager()->persist($dbUser);
        $this->getEntityManager()->flush();
    }
}
