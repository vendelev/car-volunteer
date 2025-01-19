<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\Telegram\SendPhotoCommand;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Infrastructure\Telegram\RouteParam;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class ViewPackingPhotoTelegramResponder
{
    public function __construct(
        private ButtonResponder $buttonResponder,
        private ActionRouteAccess $routeAccess,
    ) {
    }

    /**
     * @param list<string> $photoIds
     * @param list<UserRole> $roles
     * @return list<SendPhotoCommand|SendMessageCommand>
     */
    public function viewPhoto(?Uuid $parcelId, string $userId, array $photoIds, array $roles): array
    {
        $buttons = [];
        $repack = $this->routeAccess->get(ActionRouteMap::CancelPacking, $roles);

        if ($parcelId && $repack) {
            $buttons[] = [$this->buttonResponder->generate(
                actionInfo: $repack,
                param: new RouteParam('parcelId', $parcelId->toString()),
            )];
        }

        $info = $this->routeAccess->get(ActionRouteMap::ParcelList, $roles);
        if ($info) {
            $buttons[] = [$this->buttonResponder->generate(actionInfo: $info)];
        }
        $markup = $buttons ? new InlineKeyboardMarkup($buttons) : null;

        if (!$photoIds) {
            return [new SendMessageCommand($userId, 'К сожалению, фото не загружено', $markup)];
        }

        $lastId = array_pop($photoIds);
        $result = [];
        foreach ($photoIds as $photoId) {
            $result[] = new SendPhotoCommand($userId, $photoId);
        }

        $result[] = new SendPhotoCommand($userId, $lastId, null, $markup);

        return $result;
    }
}
