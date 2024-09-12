<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class EditParcelTelegramResponder
{
    /**
     * Создание сообщений для изменения описания одного заказ-наряда
     * @return list<SendMessageCommand>
     */
    public function getMessages(string $userId, ?Parcel $entity): array
    {
        if ($entity === null) {
            return [];
        }

        return [
            new SendMessageCommand(
                $userId,
                'Скопируйте и вставьте в строку ввода описание заказ-наряда или напишите новое'
            ),
            new SendMessageCommand(
                $userId,
                sprintf('<pre>%s</pre>', $entity->description),
                new InlineKeyboardMarkup(
                    [['text' => 'Отмена', 'callback_data' => '/viewParcel?id=' . $entity->id]]
                )
            )
        ];
    }
}
