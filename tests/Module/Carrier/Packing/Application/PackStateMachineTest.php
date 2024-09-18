<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Packing\Application;

use CarVolunteer\Module\Carrier\Packing\Application\PackStateMachine;
use CarVolunteer\Module\Carrier\Packing\Domain\PackStatus;
use CarVolunteer\Module\Carrier\Packing\Domain\UserClickEvent;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PackStateMachineTest extends TestCase
{
    #[DataProvider('dataProvider')]
    public function testHandle(
        PackStatus $currentStatus,
        ?UserClickEvent $clickEvent,
        ?string $photoId,
        PackStatus $expected
    ): void {
        self::assertEquals(
            $expected,
            (new PackStateMachine())->handle($currentStatus, $clickEvent, (bool)$photoId)
        );
    }

    /**
     * @return iterable<array{currentStatus: PackStatus, clickEvent: UserClickEvent|null, photoId: string|null}>
     */
    public static function dataProvider(): iterable
    {
        yield 'Статус Packed не меняется' => [
            'currentStatus' => PackStatus::Packed,
            'clickEvent' => null,
            'photoId' => null,
            'expected' => PackStatus::Packed
        ];

        yield 'Статус New не меняется' => [
            'currentStatus' => PackStatus::New,
            'clickEvent' => null,
            'photoId' => null,
            'expected' => PackStatus::New
        ];

        yield 'New -> WaitPhoto' => [
            'currentStatus' => PackStatus::New,
            'clickEvent' => UserClickEvent::LoadPhoto,
            'photoId' => null,
            'expected' => PackStatus::WaitPhoto
        ];

        yield 'New -> WaitPack' => [
            'currentStatus' => PackStatus::New,
            'clickEvent' => UserClickEvent::Packing,
            'photoId' => null,
            'expected' => PackStatus::WaitPack
        ];

        yield 'WaitPhoto -> PhotoLoaded' => [
            'currentStatus' => PackStatus::WaitPhoto,
            'clickEvent' => null,
            'photoId' => 'test',
            'expected' => PackStatus::PhotoLoaded
        ];

        yield 'WaitPhoto -> New (защита от дурака)' => [
            'currentStatus' => PackStatus::WaitPhoto,
            'clickEvent' => null,
            'photoId' => null,
            'expected' => PackStatus::New
        ];

        yield 'PhotoLoaded -> WaitPack' => [
            'currentStatus' => PackStatus::PhotoLoaded,
            'clickEvent' => UserClickEvent::Packing,
            'photoId' => null,
            'expected' => PackStatus::WaitPack
        ];

        yield 'PhotoLoaded -> New (защита от дурака)' => [
            'currentStatus' => PackStatus::PhotoLoaded,
            'clickEvent' => UserClickEvent::LoadPhoto,
            'photoId' => null,
            'expected' => PackStatus::New
        ];

        yield 'WaitPack -> Packed' => [
            'currentStatus' => PackStatus::WaitPack,
            'clickEvent' => UserClickEvent::Packed,
            'photoId' => null,
            'expected' => PackStatus::Packed
        ];

        yield 'WaitPack -> New (защита от дурака)' => [
            'currentStatus' => PackStatus::WaitPack,
            'clickEvent' => UserClickEvent::LoadPhoto,
            'photoId' => null,
            'expected' => PackStatus::New
        ];
    }
}
