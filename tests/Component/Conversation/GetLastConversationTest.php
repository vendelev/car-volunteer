<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Component\Conversation;

use CarVolunteer\Component\Conversation\Domain\Entity\Conversation;
use CarVolunteer\Component\Conversation\EntryPoint\BusHandler\GetLastConversationHandler;
use CarVolunteer\Domain\Conversation\GetLastConversationQuery;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\ReceiveMessageEvent;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\User;
use Doctrine\Persistence\ManagerRegistry;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class GetLastConversationTest extends KernelTestCase
{
    public function testHandle(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $manager = $container->get(ManagerRegistry::class)->getManager();
        $handler = $container->get(GetLastConversationHandler::class);

        $userId = '321';

        $object1 = new stdClass();
        $object1->id = 'testNil';
        $entity = new Conversation(Uuid::v7(), $userId, 'testNil', serialize($object1));
        $manager->persist($entity);

        $object = new stdClass();
        $object->id = 'test';
        $strObject = serialize($object);

        $entity = new Conversation(Uuid::v7(), $userId, 'testMax', $strObject);
        $manager->persist($entity);

        $entity = new Conversation(Uuid::v7(), '2', 'test', $strObject);
        $manager->persist($entity);

        $manager->flush();

        $result = $handler->handleQuery(new GetLastConversationQuery($userId));

        self::assertEquals('testMax', $result->actionRoute);
        self::assertEquals($object, $result->playLoad);

        $result = $handler->handleEvent(new ReceiveMessageEvent(new User(id: $userId, username: ''), null, null));

        self::assertEquals('testMax', $result->actionRoute);
        self::assertEquals($object, $result->playLoad);
    }
}