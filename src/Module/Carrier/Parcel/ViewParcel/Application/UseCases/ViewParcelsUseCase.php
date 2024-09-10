<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewParcelsUseCase
{
    public function __construct(
        private ParcelRepositoryInterface $parcelRepository
    ) {
    }

    public function handle(string $userId, MessageContext $messageContext): void
    {
        /** @var list<Parcel> $list */
        $list = $this->parcelRepository->findBy(['status' => [
            ParcelStatus::Described->value,
            ParcelStatus::Packed->value,
            ParcelStatus::Delivery->value,
            ParcelStatus::Shipped->value,
        ]]);
        $buttons = [];

        foreach ($list as $item) {
            $buttons[] = [[
                'text' => sprintf(
                    '%s%s%s %s (от %s)',
                    ($item->status === ParcelStatus::Delivered->value ? '☑' : ''),
                    ($item->packingId ? 'ⓟ' : ''),
                    ($item->deliveryId ? 'ⓓ' : ''),
                    $item->title,
                    $item->createAt->format('d.m.Y')
                ),
                'callback_data' => '/viewParcel?id=' . $item->id
            ]];
        }

        $buttons[] = [['text' => 'Помощь', 'callback_data' => '/help']];

        $messageContext->dispatch(new SendMessageCommand(
            $userId,
            'Список активных посылок',
            new InlineKeyboardMarkup($buttons)
        ));
    }
}
