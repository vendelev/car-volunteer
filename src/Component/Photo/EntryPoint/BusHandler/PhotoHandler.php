<?php

declare(strict_types=1);

namespace CarVolunteer\Component\Photo\EntryPoint\BusHandler;

use CarVolunteer\Component\Photo\Domain\Photo;
use CarVolunteer\Component\Photo\Infrastructure\Repository\PhotoRepository;
use CarVolunteer\Domain\Photo\GetAllPhotoQuery;
use CarVolunteer\Domain\Photo\SaveNewPhotoCommand;
use Doctrine\ORM\EntityManagerInterface;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;
use HardcorePhp\Infrastructure\Uuid\Uuid;

final readonly class PhotoHandler
{
    public function __construct(
        private PhotoRepository $repository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return list<string>
     */
    #[Handler]
    public function getAllPhoto(GetAllPhotoQuery $query): array
    {
        $result = $this->repository->findBy(['objectId' => $query->objectId], ['id' => 'desc']);

        return array_map(static fn(Photo $item): string => $item->photoId, $result);
    }

    #[Handler]
    public function saveNewPhoto(SaveNewPhotoCommand $command): void
    {
        $entity = new Photo(
            id: Uuid::v7(),
            objectId: $command->objectId,
            photoId: $command->photoId
        );

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
