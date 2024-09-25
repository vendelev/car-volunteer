<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Component\Photo\EntryPoint\BusHandler;

use CarVolunteer\Component\Photo\Domain\Photo;
use CarVolunteer\Component\Photo\EntryPoint\BusHandler\PhotoHandler;
use CarVolunteer\Component\Photo\Infrastructure\Repository\PhotoRepository;
use CarVolunteer\Domain\Photo\GetAllPhotoQuery;
use CarVolunteer\Domain\Photo\SaveNewPhotoCommand;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Doctrine\Persistence\ManagerRegistry;

class PhotoHandlerTest extends KernelTestCaseDecorator
{
    public function testGetAllPhoto(): void
    {
        $entity1 = new Photo(
            id: Uuid::v7(),
            objectId: Uuid::v7(),
            photoId: 'test1',
        );
        $entity2 = new Photo(
            id: Uuid::v7(),
            objectId: $entity1->objectId,
            photoId: 'test2',
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity1);
        $manager->persist($entity2);
        $manager->flush();

        $result = self::getService(PhotoHandler::class)->getAllPhoto(new GetAllPhotoQuery($entity1->objectId));
        self::assertEquals(['test2', 'test1'], $result);
    }

    public function testSaveNewPhoto(): void
    {
        $objectId = Uuid::v7();
        self::getService(PhotoHandler::class)->saveNewPhoto(new SaveNewPhotoCommand('test', $objectId));

        /** @var Photo $entity */
        $entity = self::getService(PhotoRepository::class)->findOneBy(['objectId' => $objectId]);
        self::assertEquals('test', $entity->photoId);
    }
}
