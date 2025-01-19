<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Infrastructure\Telegram\RouteParam;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class CancelPackingTelegramResponder
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
    public function cancelPacking(string $userId, Uuid $parcelId, array $roles): array
    {
        $viewInfo = $this->routeAccess->get(ActionRouteMap::PackParcel, $roles);

        if ($viewInfo === null) {
            return [];
        }

        return [
            new SendMessageCommand(
                $userId,
                'Посылка раскомплектована.',
                new InlineKeyboardMarkup([[$this->buttonResponder->generate(
                    actionInfo: $viewInfo,
                    param: new RouteParam('parcelId', $parcelId->toString()),
                )]])
            )
        ];
    }
}
