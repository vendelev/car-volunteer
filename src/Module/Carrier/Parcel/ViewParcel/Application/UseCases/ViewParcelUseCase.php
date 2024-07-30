<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewParcelUseCase
{
    public function __construct(
        private ParcelRepositoryInterface $parcelRepository
    ) {
    }

    public function handle(string $userId, string $parcelId, MessageContext $messageContext): void
    {
        /** @var Parcel|null $item */
        $item = $this->parcelRepository->findOneBy(['id' => $parcelId]);

        if ($item === null) {
            return;
        }

        $buttons = [];
        if ($item->packingId === null) {
            $buttons[] = [['text' => 'Собрать посылку', 'callback_data' => '/packParcel?parcelId=' . $parcelId]];
        }

        if ($item->deliveryId === null) {
            $buttons[] = [['text' => 'Создать доставку', 'callback_data' => '/createDelivery?parcelId=' . $parcelId]];
        }

        $buttons[] = [['text' => 'Отмена', 'callback_data' => '/viewParcels']];

        $messageContext->dispatch(new SendMessageCommand(
            $userId,
            sprintf("<b>%s</b> (от %s)\n<pre>%s</pre>", $item->title, $item->createAt->format('d.m.Y'), $item->description),
            new InlineKeyboardMarkup($buttons)
        ));
    }
}