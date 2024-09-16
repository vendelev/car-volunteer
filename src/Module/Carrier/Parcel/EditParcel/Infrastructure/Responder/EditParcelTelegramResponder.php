<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelPlayLoad;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class EditParcelTelegramResponder
{
    /**
     * Создание сообщений для изменения описания одного заказ-наряда
     * @return list<SendMessageCommand>
     */
    public function getEditMessages(string $userId, ?ParcelPlayLoad $playLoad): array
    {
        if ($playLoad === null) {
            return [];
        }

        if ($playLoad->status === ParcelStatus::WaitDescription) {
            return [
                new SendMessageCommand(
                    $userId,
                    'Скопируйте и вставьте в строку ввода описание заказ-наряда или напишите новое'
                ),
                new SendMessageCommand(
                    $userId,
                    (string)$playLoad->description,
                    new InlineKeyboardMarkup([
                        [['text' => 'Отмена', 'callback_data' => '/viewParcels']]
                    ])
                )
            ];
        }

        return [
            new SendMessageCommand(
                $userId,
                'Изменения сохранены',
                new InlineKeyboardMarkup([
                    [['text' => 'Посмотреть описание', 'callback_data' => '/viewParcel?id=' . $playLoad->id]],
                    [['text' => 'Список посылок', 'callback_data' => '/viewParcels']],
                ])
            ),
        ];
    }
}
