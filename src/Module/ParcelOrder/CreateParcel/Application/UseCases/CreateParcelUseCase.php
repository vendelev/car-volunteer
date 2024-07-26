<?php

declare(strict_types=1);

namespace CarVolunteer\Module\ParcelOrder\CreateParcel\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\ParcelOrder\Domain\Dto\Parcel;
use CarVolunteer\Module\ParcelOrder\Domain\ParcelStatus;
use CarVolunteer\Module\ParcelOrder\Domain\SaveParcelCommand;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final class CreateParcelUseCase
{
    private string $userId;
    private MessageContext $messageContext;

    public function handle(string $userId, Parcel $parcel, ?string $message, MessageContext $messageContext): Parcel
    {
        $this->userId = $userId;
        $this->messageContext = $messageContext;

        if ($parcel->status === ParcelStatus::New) {
            $result = $this->step1($parcel);
        } elseif ($parcel->status === ParcelStatus::WaitDescription && $message !== null) {
            $result = $this->step2($parcel, $message);
            $this->messageContext->dispatch(new SaveParcelCommand(userId: $userId, parcel: $result));
        } else {
            $result = $parcel;
        }

        return $result;
    }

    private function step1(Parcel $parcel): Parcel
    {
        $parcel->status = ParcelStatus::WaitDescription;

        $this->sendMessage('Введите описание посылки');

        return $parcel;
    }

    private function step2(Parcel $parcel, ?string $message): Parcel
    {
        $parcel->status = ParcelStatus::Described;
        $parcel->description = $message;

        $this->sendMessage(
            'Заказ-наряд на посылку создан',
            new InlineKeyboardMarkup([
                [['text' => 'Собрать посылку', 'callback_data' => '/pack/' . $parcel->id]],
                [['text' => 'Назначить волонтера', 'callback_data' => '/car/' . $parcel->id]],
            ])
        );

        return $parcel;
    }

    private function sendMessage(string $text, ?InlineKeyboardMarkup $buttons = null): void
    {
        $this->messageContext->dispatch(new SendMessageCommand($this->userId, $text, $buttons));
    }
}