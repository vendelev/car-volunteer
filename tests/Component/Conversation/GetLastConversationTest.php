<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Component\Conversation;

use CarVolunteer\Component\Conversation\Domain\Entity\Conversation;
use CarVolunteer\Component\Conversation\EntryPoint\BusHandler\GetLastConversationHandler;
use CarVolunteer\Domain\ActionRoute;
use CarVolunteer\Domain\Conversation\GetLastConversationQuery;
use Doctrine\Persistence\ManagerRegistry;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class GetLastConversationTest extends KernelTestCase
{
    public function testHandle(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $manager = $container->get(ManagerRegistry::class)->getManager(); // @phpstan-ignore-line

        /** @var GetLastConversationHandler $handler */
        $handler = $container->get(GetLastConversationHandler::class);

        $userId = (string)time();

        $object1 = new stdClass();
        $object1->id = 'testNil';
        $entity = new Conversation(
            id: Uuid::v7(),
            userId: $userId,
            actionRoute: (array)new ActionRoute('testNil'),
            playLoad: (array)$object1
        );
        $manager->persist($entity);

        $object = new stdClass();
        $object->id = 'test';

        $entity = new Conversation(
            id: Uuid::v7(),
            userId: $userId,
            actionRoute: (array)new ActionRoute('testMax'),
            playLoad: (array)$object
        );
        $manager->persist($entity);

        $entity = new Conversation(
            id: Uuid::v7(),
            userId: '2',
            actionRoute: (array)new ActionRoute('test'),
            playLoad: (array)$object
        );
        $manager->persist($entity);

        $manager->flush();

        $result = $handler->handleQuery(new GetLastConversationQuery($userId));

        self::assertEquals('testMax', $result?->actionRoute->route);
        self::assertEquals((array)$object, $result?->playLoad);
    }
}
