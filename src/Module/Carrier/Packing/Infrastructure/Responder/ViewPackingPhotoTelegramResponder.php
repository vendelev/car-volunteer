<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\Telegram\SendPhotoCommand;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class ViewPackingPhotoTelegramResponder
{
    public function __construct(
        private ButtonResponder $buttonResponder,
        private ActionRouteAccess $routeAccess,
    ) {
    }

    /**
     * @param list<UserRole> $roles
     */
    public function viewPhoto(string $userId, ?string $photoId, array $roles): SendPhotoCommand|SendMessageCommand
    {
        $buttons = [];
        $info = $this->routeAccess->get(ActionRouteMap::ParcelList, $roles);
        if ($info) {
            $buttons[] = [$this->buttonResponder->generate(actionInfo: $info)];
        }
        $markup = $buttons ? new InlineKeyboardMarkup($buttons) : null;

        if (!$photoId) {
            return new SendMessageCommand($userId, 'К сожалению, фото не загружено', $markup);
        }

        return new SendPhotoCommand($userId, $photoId, null, $markup);
    }
}
