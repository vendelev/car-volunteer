<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelPlayLoad;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelCreatedEvent;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final class CreateParcelUseCase
{
    private string $userId;
    private MessageContext $messageContext;

    public function handle(
        string $userId,
        ParcelPlayLoad $parcel,
        ?string $message,
        MessageContext $messageContext
    ): ParcelPlayLoad {
        $this->userId = $userId;
        $this->messageContext = $messageContext;

        if ($parcel->status === ParcelStatus::New) {
            $result = $this->step1($parcel);
        } elseif ($parcel->status === ParcelStatus::WaitTitle && $message !== null) {
            $result = $this->step2($parcel, $message);
        } elseif ($parcel->status === ParcelStatus::WaitDescription && $message !== null) {
            $result = $this->step3($parcel, $message);
            $messageContext->dispatch(new ParcelCreatedEvent(userId: $userId, parcel: $result));
        } else {
            $result = $parcel;
        }

        return $result;
    }

    private function step1(ParcelPlayLoad $parcel): ParcelPlayLoad
    {
        $parcel->status = ParcelStatus::WaitTitle;

        $this->sendMessage('Введите название посылки');

        return $parcel;
    }

    private function step2(ParcelPlayLoad $parcel, string $message): ParcelPlayLoad
    {
        $parcel->status = ParcelStatus::WaitDescription;
        $parcel->title = $message;

        $this->sendMessage('Введите описание посылки');

        return $parcel;
    }

    private function step3(ParcelPlayLoad $parcel, string $message): ParcelPlayLoad
    {
        $parcel->status = ParcelStatus::Described;
        $parcel->description = $message;

        $this->sendMessage(
            'Заказ-наряд на посылку создан',
            new InlineKeyboardMarkup([
                [['text' => 'Посмотреть', 'callback_data' => '/viewParcel?id=' . $parcel->id]],
                [['text' => 'Собрать посылку', 'callback_data' => '/packParcel?parcelId=' . $parcel->id]],
                [['text' => 'Создать доставку', 'callback_data' => '/createDelivery?parcelId=' . $parcel->id]],
                [['text' => 'В начало', 'callback_data' => '/help']],
            ])
        );

        return $parcel;
    }

    private function sendMessage(string $text, ?InlineKeyboardMarkup $buttons = null): void
    {
        $this->messageContext->dispatch(new SendMessageCommand($this->userId, $text, $buttons));
    }
}
