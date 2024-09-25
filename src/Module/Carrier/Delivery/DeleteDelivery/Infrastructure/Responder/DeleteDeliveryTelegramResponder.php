<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\DeleteDelivery\Infrastructure\Responder;

use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Infrastructure\Telegram\RouteParam;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class DeleteDeliveryTelegramResponder
{
    public function __construct(
        private ButtonResponder $buttonResponder,
        private ActionRouteAccess $routeAccess,
    ) {
    }

    /**
     * @param list<UserRole> $roles
     */
    public function getBeforeDeleteButtons(ActionInfo $selfInfo, array $roles, Uuid $parcelId): ?InlineKeyboardMarkup
    {
        $result = [
            [$this->buttonResponder->generate(
                $selfInfo,
                new RouteParam('confirm', '1'),
                'Подтверждаю'
            )]
        ];

        $info = $this->routeAccess->get(ActionRouteMap::ParcelView, $roles);
        if ($info) {
            $result[] = [$this->buttonResponder->generate(
                $info,
                new RouteParam('id', $parcelId->toString()),
                'Отмена'
            )];
        }

        return new InlineKeyboardMarkup($result);
    }

    /**
     * @param list<UserRole> $roles
     */
    public function getAfterDeleteButtons(array $roles, Uuid $parcelId): ?InlineKeyboardMarkup
    {
        $info = $this->routeAccess->get(ActionRouteMap::ParcelView, $roles);
        if ($info) {
            $result = $this->buttonResponder->generate(
                $info,
                new RouteParam('id', $parcelId->toString()),
            );
            return new InlineKeyboardMarkup([[$result]]);
        }

        return null;
    }
}
