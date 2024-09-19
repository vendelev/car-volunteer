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
     * @param class-string $expectedType
     */
    #[DataProvider('dataProvider')]
    public function testViewPhoto(?string $photoId, string $expectedType): void
    {
        $command = self::getService(ViewPackingPhotoTelegramResponder::class)->viewPhoto(
            '1',
            $photoId,
            []
        );

        self::assertInstanceOf($expectedType, $command);
    }

    /**
     * @return iterable<array{photoId: string|null, expectedType: class-string}>
     */
    public static function dataProvider(): iterable
    {
        yield 'Есть фото' => [
            'photoId' => '1',
            'expectedType' => SendPhotoCommand::class
        ];

        yield 'Нет фото' => [
            'photoId' => null,
            'expectedType' => SendMessageCommand::class
        ];
    }
}
