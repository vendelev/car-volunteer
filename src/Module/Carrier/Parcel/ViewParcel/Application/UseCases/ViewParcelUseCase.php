<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Domain\Entity\Parcel;
use CarVolunteer\Module\Carrier\Domain\ParcelRepositoryInterface;
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

        $messageContext->dispatch(new SendMessageCommand(
            $userId,
            sprintf("<b>%s</b> (от %s)\n<pre>%s</pre>", $item->title, $item->createAt->format('d.m.Y'), $item->description),
            new InlineKeyboardMarkup([
                [['text' => 'Собрать посылку', 'callback_data' => '/packParcel?parcelId=' . $parcelId]],
                [['text' => 'Создать доставку', 'callback_data' => '/createDelivery?parcelId=' . $parcelId]],
            ])
        ));
    }
}