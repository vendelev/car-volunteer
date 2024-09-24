<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
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
        if ($carrierId === 'virtual') {
            return;
        }

        $this->messageBus->dispatch(new SendMessageCommand(
            $carrierId,
            'Вам назначена посылка для доставки',
            new InlineKeyboardMarkup([
                [['text' => 'Посмотреть посылку', 'callback_data' => ActionRouteMap::ParcelView->value . '?id=' . $parcelId]],
                [['text' => 'Список посылок', 'callback_data' => ActionRouteMap::ParcelList->value]],
                [['text' => 'В начало', 'callback_data' => ActionRouteMap::RootHelp->value]],
            ])
        ));
    }
}
