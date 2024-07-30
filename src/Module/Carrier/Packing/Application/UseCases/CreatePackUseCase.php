<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Domain\ParcelPackedEvent;
use CarVolunteer\Module\Carrier\Packing\Domain\PackPlayLoad;
use CarVolunteer\Module\Carrier\Packing\Domain\PackStatus;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class CreatePackUseCase
{
    private string $userId;
    private MessageContext $messageContext;

    public function handle(string $userId, PackPlayLoad $pack, bool $confirm, MessageContext $messageContext): PackPlayLoad
    {
        $this->userId = $userId;
        $this->messageContext = $messageContext;

        if ($pack->status === PackStatus::New) {
            $result = $this->step1($pack);
        } elseif ($pack->status === PackStatus::WaitPack) {
            $result = $this->step2($pack, $confirm);
            $messageContext->dispatch(new ParcelPackedEvent($userId, $result->parcelId, $result->id));
        } else {
            $result = $pack;
        }

        return $result;
    }

    private function step1(PackPlayLoad $pack): PackPlayLoad
    {
        $pack->status = PackStatus::WaitPack;

        $this->sendMessage(
            'Подтвердите, что посылка собрана и готова к отгрузке',
            new InlineKeyboardMarkup([
                [['text' => 'Посылка готова', 'callback_data' => '/packParcel?confirm=1']],
                [['text' => 'Отмена', 'callback_data' => '/viewParcels']],
            ])
        );

        return $pack;
    }

    private function step2(PackPlayLoad $pack, bool $confirm): PackPlayLoad
    {
        if (!$confirm) {
            return $this->step1($pack);
        }

        $pack->status = PackStatus::Packed;

        $this->sendMessage(
            'Посылка готова к отгрузке',
            new InlineKeyboardMarkup([
                [['text' => 'Создать доставку', 'callback_data' => '/createDelivery?parcelId=' . $pack->parcelId]],
                [['text' => 'Список посылок', 'callback_data' => '/viewParcels']],
                [['text' => 'Помощь', 'callback_data' => '/help']],
            ])
        );

        return $pack;
    }

    private function sendMessage(string $text, ?InlineKeyboardMarkup $buttons = null): void
    {
        $this->messageContext->dispatch(new SendMessageCommand($this->userId, $text, $buttons));
    }
}