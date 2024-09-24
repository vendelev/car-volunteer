<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class EditParcelTelegramResponder
{
    public function __construct(
        private ButtonResponder $buttonResponder,
        private ActionRouteAccess $routeAccess,
    ) {
    }

    /**
     * @param list<UserRole> $roles
     * @return list<SendMessageCommand>
     */
    public function getEditTitleButton(string $userId, string $text, array $roles): array
    {
        $result = [new SendMessageCommand(
            $userId,
            'Скопируйте и вставьте в строку ввода "Название" или напишите новое'
        )];

        $result[] = $this->getEditCancelButton($userId, $text, $roles);

        return $result;
    }

    /**
     * @param list<UserRole> $roles
     * @return list<SendMessageCommand>
     */
    public function getEditDescriptionButton(string $userId, string $text, array $roles): array
    {
        $result = [new SendMessageCommand(
            $userId,
            'Скопируйте и вставьте в строку ввода "Описание" или напишите новое'
        )];

        $result[] = $this->getEditCancelButton($userId, $text, $roles);

        return $result;
    }

    /**
     * @param list<UserRole> $roles
     */
    public function getEditCancelButton(string $userId, string $text, array $roles): SendMessageCommand
    {
        /** @var ActionInfo $parcelListInfo */
        $parcelListInfo = $this->routeAccess->get(ActionRouteMap::ParcelList, $roles);

        return new SendMessageCommand(
            $userId,
            $text,
            new InlineKeyboardMarkup([
                [$this->buttonResponder->generate(actionInfo: $parcelListInfo, title: 'Отмена')]
            ])
        );
    }

    /**
     * @param list<UserRole> $roles
     * @return list<SendMessageCommand>
     */
    public function getAfterSave(string $userId, array $roles): array
    {
        $info = $this->routeAccess->get(ActionRouteMap::ParcelList, $roles);
        if ($info) {
            return [new SendMessageCommand(
                $userId,
                'Информация сохранена',
                new InlineKeyboardMarkup([[$this->buttonResponder->generate($info)]])
            )];
        }

        return [];
    }
}
