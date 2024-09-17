<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\CreateParcel\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Infrastructure\Telegram\RouteParam;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelPlayLoad;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class NotifyTelegramResponder
{
    public function __construct(
        private ButtonResponder $buttonResponder,
        private ActionRouteAccess $routeAccess,
    ) {
    }

    /**
     * @param list<string> $users
     * @param list<UserRole> $roles
     * @return list<SendMessageCommand>
     */
    public function getCreateParcelMessages(array $users, ParcelPlayLoad $playLoad, array $roles): array
    {
        $result = [];
        $actionInfo = $this->routeAccess->get(ActionRouteMap::ParcelView, $roles);
        if ($actionInfo) {
            $buttons = new InlineKeyboardMarkup([[
                $this->buttonResponder->generate($actionInfo, new RouteParam('id', $playLoad->id->toString()))
            ]]);
        } else {
            $buttons = null;
        }

        foreach ($users as $userId) {
            $result[] = new SendMessageCommand(
                $userId,
                sprintf('Создан новый заказ-наряд: <b>%s</b>', $playLoad->title),
                $buttons
            );
        }

        return $result;
    }
}
