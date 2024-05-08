<?php

declare(strict_types=1);

namespace CarVolunteer\Repository;

use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(TelegramMessage $message): void
    {
        $dbUser = new User;
        $dbUser->setId($message->user->id);
        $dbUser->setUsername($message->user->username);

        $this->getEntityManager()->persist($dbUser);
        $this->getEntityManager()->flush();
    }
}