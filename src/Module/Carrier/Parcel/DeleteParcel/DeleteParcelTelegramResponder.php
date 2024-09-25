<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\DeleteParcel;

use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Infrastructure\Telegram\RouteParam;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class DeleteParcelTelegramResponder
{
    public function __construct(
        private ButtonResponder $buttonResponder,
        private ActionRouteAccess $routeAccess,
    ) {
    }

    /**
     * @param list<UserRole> $roles
     */
    public function getBeforeDeleteButtons(ActionInfo $selfInfo, array $roles): ?InlineKeyboardMarkup
    {
        $result = [
            [$this->buttonResponder->generate(
                $selfInfo,
                new RouteParam('confirm', '1'),
                'Подтверждаю'
            )]
        ];

        $info = $this->routeAccess->get(ActionRouteMap::ParcelList, $roles);
        if ($info) {
            $result[] = [$this->buttonResponder->generate($info, null, 'Отмена')];
        }

        return new InlineKeyboardMarkup($result);
    }

    /**
     * @param list<UserRole> $roles
     */
    public function getAfterDeleteButtons(array $roles): ?InlineKeyboardMarkup
    {
        $info = $this->routeAccess->get(ActionRouteMap::ParcelList, $roles);
        if ($info) {
            return new InlineKeyboardMarkup([[$this->buttonResponder->generate($info)]]);
        }

        return null;
    }
}
