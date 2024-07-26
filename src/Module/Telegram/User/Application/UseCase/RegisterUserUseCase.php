<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\User\Application\UseCase;

use CarVolunteer\Module\Telegram\Domain\User as UserDto;
use CarVolunteer\Module\Telegram\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final readonly class RegisterUserUseCase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(UserDto $userDto): void
    {
        $entity = new User(
            id: $userDto->id,
            username: $userDto->username,
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}