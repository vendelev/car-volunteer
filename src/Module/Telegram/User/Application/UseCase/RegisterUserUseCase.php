<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\User\Application\UseCase;

use CarVolunteer\Module\Telegram\Domain\User as UserDto;
use CarVolunteer\Module\Telegram\User\Entity\User;
use CarVolunteer\Module\Telegram\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class RegisterUserUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $repository
    ) {
    }

    public function handle(UserDto $userDto): void
    {
        if ($this->repository->findOneBy(['id' => $userDto->id])) {
            return;
        }

        $entity = new User(
            id: $userDto->id,
            username: $userDto->username,
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}