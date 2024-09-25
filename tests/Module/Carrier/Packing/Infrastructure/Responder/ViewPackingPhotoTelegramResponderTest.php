<?php

declare(strict_types=1);

namespace CarVolunteer\Tests\Module\Carrier\Packing\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\Telegram\SendPhotoCommand;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder\ViewPackingPhotoTelegramResponder;
use CarVolunteer\Tests\KernelTestCaseDecorator;
use PHPUnit\Framework\Attributes\DataProvider;

class ViewPackingPhotoTelegramResponderTest extends KernelTestCaseDecorator
{
    /**
     * @param list<string> $photoIds
     * @param list<class-string> $expectedTypes
     */
    #[DataProvider('dataProvider')]
    public function testViewPhoto(array $photoIds, array $expectedTypes): void
    {
        $commands = self::getService(ViewPackingPhotoTelegramResponder::class)->viewPhoto(
            '1',
            $photoIds,
            []
        );

        foreach ($expectedTypes as $index => $type) {
            self::assertInstanceOf($type, $commands[$index]);
        }
    }

    /**
     * @return iterable<array{photoIds: list<string>, expectedTypes: list<class-string>}>
     */
    public static function dataProvider(): iterable
    {
        yield 'Есть фото' => [
            'photoIds' => ['1'],
            'expectedTypes' => [SendPhotoCommand::class]
        ];

        yield 'Нет фото' => [
            'photoIds' => [],
            'expectedTypes' => [SendMessageCommand::class]
        ];
    }
}
