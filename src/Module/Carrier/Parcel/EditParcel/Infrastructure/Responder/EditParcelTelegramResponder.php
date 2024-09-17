<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelPlayLoad;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class EditParcelTelegramResponder
{
    public function __construct(
        private ButtonResponder $buttonResponder,
        private ActionRouteAccess $routeAccess,
    ) {
    }

    /**
     * Создание сообщений для изменения описания одного заказ-наряда
     *
     * @param list<UserRole> $roles
     * @return list<SendMessageCommand>
     */
    public function getEditMessages(string $userId, ?ParcelPlayLoad $playLoad, array $roles): array
    {
        if ($playLoad === null) {
            return [];
        }

        /** @var ActionInfo $parcelListInfo */
        $parcelListInfo = $this->routeAccess->get(ActionRouteMap::ParcelList, $roles);

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
                        [$this->buttonResponder->generate(actionInfo: $parcelListInfo, title: 'Отмена')]
                    ])
                )
            ];
        }

        /** @var ActionInfo $parcelViewInfo */
        $parcelViewInfo = $this->routeAccess->get(ActionRouteMap::ParcelView, $roles);

        return [
            new SendMessageCommand(
                $userId,
                'Изменения сохранены',
                new InlineKeyboardMarkup([
                    [$this->buttonResponder->generate($parcelViewInfo)],
                    [$this->buttonResponder->generate($parcelListInfo)],
                ])
            ),
        ];
    }
}
