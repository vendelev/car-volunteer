<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\DeleteParcel\Infrastructure\Responder;

use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Infrastructure\Telegram\RouteParam;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class DeleteParcelResponder
{
    public function __construct(
        private ButtonResponder $buttonResponder,
        private ActionRouteAccess $routeAccess,
    ) {
    }

    public function getBeforeDeleteButtons(ActionInfo $selfInfo): ?InlineKeyboardMarkup
    {
        return new InlineKeyboardMarkup([
            [$this->buttonResponder->generate(
                $selfInfo,
                new RouteParam('confirm', '1'),
                'Подтверждаю'
            )]
        ]);
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
