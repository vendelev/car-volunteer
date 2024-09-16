<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\CreateParcel\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelPlayLoad;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class NotifyTelegramResponder
{
    /**
     * @param list<string> $users
     * @return list<SendMessageCommand>
     */
    public function getCreateParcelMessages(array $users, ParcelPlayLoad $playLoad): array
    {
        $result = [];
        foreach ($users as $userId) {
            $result[] = new SendMessageCommand(
                $userId,
                sprintf('Создан новый заказ-наряд: <b>%s</b>', $playLoad->title),
                new InlineKeyboardMarkup([
                    [['text' => 'Посмотреть', 'callback_data' => '/viewParcel?id=' . $playLoad->id]]
                ])
            );
        }

        return $result;
    }
}