<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageBus;

final readonly class NotifyNewDeliveryUseCase
{
    public function __construct(
        private MessageBus $messageBus,
    ) {
    }

    public function handle(string $carrierId, Uuid $parcelId): void
    {
        $this->messageBus->dispatch(new SendMessageCommand(
            $carrierId,
            'Вам назначена посылка для доставки',
            new InlineKeyboardMarkup([
                [['text' => 'Посмотреть посылку', 'callback_data' => '/viewParcel?id=' . $parcelId]],
                [['text' => 'Список посылок', 'callback_data' => '/viewParcels']],
                [['text' => 'Помощь', 'callback_data' => '/help']],
            ])
        ));
    }
}