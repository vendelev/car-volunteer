<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Parcel\EditParcel\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelChangeDescriptionEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelChangeTitleEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Application\UseCases\EditParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Domain\EditParcelPlayLoad;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use Doctrine\Persistence\ManagerRegistry;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use PHPUnit\Framework\Attributes\DataProvider;

class EditParcelUseCaseTest extends KernelTestCaseDecorator
{
    /**
     * @param list<UserRole> $roles
     */
    #[DataProvider('dataProviderNotAllowed')]
    public function testEditTileNotAllowed(EditParcelPlayLoad $playLoad, array $roles): void
    {
        $commands = self::getService(EditParcelUseCase::class)->editTitle('1', $playLoad, $roles);

        self::assertCount(1, $commands);

        /** @var SendMessageCommand $command */
        $command = $commands[0];
        self::assertEquals('Редактирование не возможно', $command->text);
    }

    /**
     * @param list<UserRole> $roles
     */
    #[DataProvider('dataProviderNotAllowed')]
    public function testEditDescriptionNotAllowed(EditParcelPlayLoad $playLoad, array $roles): void
    {
        $commands = self::getService(EditParcelUseCase::class)->editDescription('1', $playLoad, $roles);

        self::assertCount(1, $commands);

        /** @var SendMessageCommand $command */
        $command = $commands[0];
        self::assertEquals('Редактирование не возможно', $command->text);
    }

    /**
     * @return iterable<array{playLoad: EditParcelPlayLoad, roles: list<UserRole>, expectedText: string}>
     */
    public static function dataProviderNotAllowed(): iterable
    {
        $entity1 = new Parcel(
            id: Uuid::v7(),
            authorId: '1',
            status: ParcelStatus::Described->value,
            title: '',
            description: '',
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity1);
        $manager->flush();

        yield 'Посылка не найдена' => [
            'playLoad' => new EditParcelPlayLoad(Uuid::v7(), null),
            'roles' => [UserRole::Manager, UserRole::User],
            'expectedText' => 'Редактирование не возможно',
        ];

        $entity2 = new Parcel(
            id: Uuid::v7(),
            authorId: '1',
            status: ParcelStatus::Delivered->value,
            title: '',
            description: '',
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity2);
        $manager->flush();

        yield 'Посылка доставлена' => [
            'playLoad' => new EditParcelPlayLoad($entity2->id, null),
            'roles' => [UserRole::Manager, UserRole::User],
            'expectedText' => 'Редактирование не возможно',
        ];

        yield 'Не хватает прав' => [
            'playLoad' => new EditParcelPlayLoad($entity1->id, null),
            'roles' => [UserRole::User],
            'expectedText' => 'Редактирование не возможно',
        ];
    }

    /**
     * @param list<UserRole> $roles
     * @param list<class-string> $expected
     */
    #[DataProvider('dataProviderForEditTitle')]
    public function testEditTitle(EditParcelPlayLoad $playLoad, array $roles, array $expected): void
    {
        $commands = self::getService(EditParcelUseCase::class)->editTitle('1', $playLoad, $roles);

        self::assertCount(2, $commands);

        foreach ($expected as $index => $className) {
            self::assertInstanceOf($className, $commands[$index]);
        }
    }

    /**
     * @return iterable<array{
     *     playLoad: EditParcelPlayLoad,
     *     roles: list<UserRole>,
     *     expected: list<class-string>
     * }>
     */
    public static function dataProviderForEditTitle(): iterable
    {
        $entity1 = new Parcel(
            id: Uuid::v7(),
            authorId: '1',
            status: ParcelStatus::Described->value,
            title: '',
            description: '',
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity1);
        $manager->flush();

        yield 'Шаг 1' => [
            'playLoad' => new EditParcelPlayLoad($entity1->id, null),
            'roles' => [UserRole::Manager, UserRole::User],
            'expected' => [SendMessageCommand::class, SendMessageCommand::class],
        ];

        yield 'Шаг 2' => [
            'playLoad' => new EditParcelPlayLoad($entity1->id, 'test'),
            'roles' => [UserRole::Manager, UserRole::User],
            'expected' => [ParcelChangeTitleEvent::class, SendMessageCommand::class],
        ];
    }

    /**
     * @param list<UserRole> $roles
     * @param list<class-string> $expected
     */
    #[DataProvider('dataProviderForEditDesc')]
    public function testEditDescription(EditParcelPlayLoad $playLoad, array $roles, array $expected): void
    {
        $commands = self::getService(EditParcelUseCase::class)->editDescription('1', $playLoad, $roles);

        self::assertCount(2, $commands);

        foreach ($expected as $index => $className) {
            self::assertInstanceOf($className, $commands[$index]);
        }
    }

    /**
     * @return iterable<array{
     *     playLoad: EditParcelPlayLoad,
     *     roles: list<UserRole>,
     *     expected: list<class-string>
     * }>
     */
    public static function dataProviderForEditDesc(): iterable
    {
        $entity1 = new Parcel(
            id: Uuid::v7(),
            authorId: '1',
            status: ParcelStatus::Described->value,
            title: '',
            description: '',
        );

        $manager = self::getService(ManagerRegistry::class)->getManager();
        $manager->persist($entity1);
        $manager->flush();

        yield 'Шаг 1' => [
            'playLoad' => new EditParcelPlayLoad($entity1->id, null),
            'roles' => [UserRole::Manager, UserRole::User],
            'expected' => [SendMessageCommand::class, SendMessageCommand::class],
        ];

        yield 'Шаг 2' => [
            'playLoad' => new EditParcelPlayLoad($entity1->id, 'test'),
            'roles' => [UserRole::Manager, UserRole::User],
            'expected' => [ParcelChangeDescriptionEvent::class, SendMessageCommand::class],
        ];
    }
}
