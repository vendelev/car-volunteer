<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Infrastructure\Telegram\RouteParam;
use CarVolunteer\Module\Carrier\Packing\Domain\PackStatus;
use CarVolunteer\Module\Carrier\Packing\Domain\UserClickEvent;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class CreatePackTelegramResponder
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
    public function createPack(string $userId, PackStatus $status, Uuid $parcelId, array $roles): array
    {
        $packInfo = $this->routeAccess->get(ActionRouteMap::PackParcel, $roles);
        if ($packInfo === null) {
            return [];
        }

        $buttons = [];

        $viewInfo = $this->routeAccess->get(ActionRouteMap::ParcelView, $roles);
        if ($viewInfo) {
            $buttons[] = $this->buttonResponder->generate(
                actionInfo: $viewInfo,
                param: new RouteParam('id', $parcelId->toString()),
                title: 'Отмена'
            );
        }

        switch ($status) {
            case PackStatus::New:
                array_unshift(
                    $buttons,
                    $this->buttonResponder->generate(
                        actionInfo: $packInfo,
                        param: new RouteParam('want', UserClickEvent::LoadPhoto->value),
                        title: 'Добавить фото'
                    ),
                    $this->buttonResponder->generate(
                        actionInfo: $packInfo,
                        param: new RouteParam('want', UserClickEvent::Packing->value),
                        title: 'Посылка собрана и готова к отгрузке'
                    )
                );
                $messageText = 'Добавьте фото или подтвердите, что посылка собрана и готова к отгрузке';
                break;

            case PackStatus::WaitPhoto:
                $buttons = [];
                $messageText = 'Отправьте несколько фотографий в одном сообщении';
                break;

            case PackStatus::PhotoLoaded:
                array_unshift(
                    $buttons,
                    $this->buttonResponder->generate(
                        actionInfo: $packInfo,
                        param: new RouteParam('want', UserClickEvent::Packing->value),
                        title: 'Посылка собрана и готова к отгрузке'
                    )
                );
                $messageText = 'Подтвердите, что посылка собрана и готова к отгрузке';
                break;

            case PackStatus::WaitPack:
                array_unshift(
                    $buttons,
                    $this->buttonResponder->generate(
                        actionInfo: $packInfo,
                        param: new RouteParam('want', UserClickEvent::Packed->value),
                        title: 'Подтверждаю'
                    )
                );
                $messageText = 'Нажмите кнопку "Подтверждаю" или "Отмена"';
                break;

            case PackStatus::Packed:
                $buttons = [];

                $listInfo = $this->routeAccess->get(ActionRouteMap::ParcelList, $roles);
                if ($listInfo) {
                    $buttons[] = $this->buttonResponder->generate($listInfo);
                }

                $info = $this->routeAccess->get(ActionRouteMap::RootHelp, $roles);
                if ($info) {
                    $buttons[] = $this->buttonResponder->generate($info);
                }

                $messageText = 'Посылка собрана';
                break;
            default:
                $messageText = null;
        }

        if ($messageText !== null) {
            return [
                new SendMessageCommand(
                    $userId,
                    $messageText,
                    $buttons ? new InlineKeyboardMarkup(array_map(static fn ($button) => [$button], $buttons)) : null
                )
            ];
        }

        return [];
    }
}
