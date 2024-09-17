<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Infrastructure\Telegram;

use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use PHPUnit\Framework\Attributes\DataProvider;

class ActionRouteAccessTest extends KernelTestCaseDecorator
{
    public function testGetInfo(): void
    {
        $service = self::getService(ActionRouteAccess::class);
        self::assertNotNull($service->get(ActionRouteMap::DeliveryFinish, [UserRole::Volunteer]));
    }
    public function testGetWithNoAccess(): void
    {
        $service = self::getService(ActionRouteAccess::class);
        self::assertNull($service->get(ActionRouteMap::DeliveryFinish, [UserRole::Manager]));
    }

    /**
     * @param list<UserRole> $accessRoles
     * @param list<UserRole> $userRoles
     */
    #[DataProvider('dataProviderForTestCan')]
    public function testCan(array $accessRoles, array $userRoles, bool $expected): void
    {
        $service = self::getService(ActionRouteAccess::class);
        self::assertEquals($expected, $service->can($accessRoles, $userRoles));
    }

    /**
     * @return iterable<array{accessRoles: list<UserRole>, userRoles: list<UserRole>, expected: bool}>
     */
    public static function dataProviderForTestCan(): iterable
    {
        yield 'Доступ для админа' => [
            'accessRoles' => [UserRole::Receiver],
            'userRoles' => [UserRole::Admin],
            'expected' => true,
        ];

        yield 'Проверка доступа' => [
            'accessRoles' => [UserRole::Manager, UserRole::Receiver],
            'userRoles' => [UserRole::Volunteer, UserRole::Manager],
            'expected' => true,
        ];

        yield 'Доступ запрещен' => [
            'accessRoles' => [UserRole::Manager, UserRole::Receiver],
            'userRoles' => [UserRole::Volunteer, UserRole::Picker],
            'expected' => false,
        ];

        yield 'У пользователя нет ролей' => [
            'accessRoles' => [UserRole::Manager, UserRole::Receiver],
            'userRoles' => [],
            'expected' => false,
        ];
    }
}
