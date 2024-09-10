<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\User\Application\UseCase;

use CarVolunteer\Module\Telegram\Domain\User as UserDto;
use CarVolunteer\Module\Telegram\User\Entity\User;
use CarVolunteer\Module\Telegram\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SyncUserUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $repository
    ) {
    }

    public function handle(UserDto $userDto): void
    {
        $entity = $this->repository->findOneBy(['id' => $userDto->id]);

        if ($entity === null) {
            $entity = new User(
                id: $userDto->id,
                username: $userDto->username,
                firstName: $userDto->firstName,
                lastName: $userDto->lastName,
            );
        } else {
            $entity->username = $userDto->username;
            $entity->firstName = $userDto->firstName;
            $entity->lastName = $userDto->lastName;
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
