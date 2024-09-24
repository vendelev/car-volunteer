<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\Infrastructure\Responder;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Infrastructure\Telegram\RouteParam;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Domain\ViewParcelModel;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class ViewParcelTelegramResponder
{
    public function __construct(
        private ButtonResponder $buttonResponder,
        private ActionRouteAccess $routeAccess,
    ) {
    }

    /**
     * Создание сообщения показа одного заказ-наряда
     *
     * @param list<UserRole> $roles
     */
    public function viewParcel(string $userId, ViewParcelModel $model, array $roles): SendMessageCommand
    {
        $buttons = [];
        $parcelId = $model->parcel->id->toString();

        foreach ($model->actions as $action) {
            switch ($action) {
                case ActionRouteMap::ParcelEditTitle:
                case ActionRouteMap::ParcelEditDescription:
                    $info = $this->routeAccess->get($action, $roles, true);
                    $param = new RouteParam('id', $parcelId);
                    break;
                case ActionRouteMap::ParcelDelete:
                    $info = $this->routeAccess->get($action, $roles);
                    $param = new RouteParam('id', $parcelId);
                    break;
                case ActionRouteMap::PackParcel:
                case ActionRouteMap::DeliveryCreate:
                case ActionRouteMap::DeliveryFinish:
                    $info = $this->routeAccess->get($action, $roles);
                    $param = new RouteParam('parcelId', $parcelId);
                    break;
                case ActionRouteMap::DeliveryView:
                    $info = $this->routeAccess->get($action, $roles);
                    $param = new RouteParam('id', (string)$model->parcel->deliveryId?->toString());
                    break;
                case ActionRouteMap::PackingPhoto:
                    $info = $this->routeAccess->get($action, $roles);
                    $param = new RouteParam('id', (string)$model->parcel->packingId?->toString());
                    break;
                default:
                    $info = null;
                    $param = null;
                    break;
            }

            if ($info) {
                $buttons[] = $this->buttonResponder->generate($info, $param);
            }
        }

        $info = $this->routeAccess->get(ActionRouteMap::ParcelList, $roles);
        if ($info) {
            $buttons[] = $this->buttonResponder->generate(actionInfo: $info, title: 'Отмена');
        }

        $item = $model->parcel;

        return new SendMessageCommand(
            $userId,
            sprintf(
                "<b>%s</b> (от %s)<pre>%s</pre>%s\n%s\n%s",
                $item->title,
                $item->createAt->format('d.m.Y'),
                $item->description,
                ($item->packingId ? 'ⓟ Упаковано' : ''),
                ($item->deliveryId ? 'ⓓ Доставка запланирована' : ''),
                ($item->status === ParcelStatus::Delivered->value ? '☑ Доставлено' : '')
            ),
            new InlineKeyboardMarkup(array_map(static fn ($button) => [$button], $buttons))
        );
    }

    /**
     * Создание сообщения для вывода списка активных заказ-нарядов
     *
     * @param list<Parcel> $list
     * @param list<UserRole> $roles
     */
    public function viewActiveParcels(string $userId, array $list, array $roles): SendMessageCommand
    {
        $buttons = $this->getParcelButtons($list, $roles);

        return new SendMessageCommand(
            $userId,
            'Список активных посылок',
            new InlineKeyboardMarkup($buttons)
        );
    }

    /**
     * Создание сообщения для вывода списка доставленных заказ-нарядов
     *
     * @param list<Parcel> $list
     * @param list<UserRole> $roles
     */
    public function viewArchiveParcels(string $userId, array $list, array $roles): SendMessageCommand
    {
        $buttons = $this->getParcelButtons($list, $roles);

        return new SendMessageCommand(
            $userId,
            'Список доставленных посылок',
            new InlineKeyboardMarkup($buttons)
        );
    }

    /**
     * @param list<Parcel> $list
     * @param list<UserRole> $roles
     * @return array<mixed>
     */
    private function getParcelButtons(array $list, array $roles): array
    {
        $buttons = [];

        $info = $this->routeAccess->get(ActionRouteMap::ParcelView, $roles);

        if ($info) {
            foreach ($list as $item) {
                $buttons[] = [$this->buttonResponder->generate(
                    actionInfo: $info,
                    param: new RouteParam('id', $item->id->toString()),
                    title: sprintf(
                        '%s%s%s %s (от %s)',
                        ($item->status === ParcelStatus::Delivered->value ? '☑' : ''),
                        ($item->packingId ? 'ⓟ' : ''),
                        ($item->deliveryId ? 'ⓓ' : ''),
                        $item->title,
                        $item->createAt->format('d.m.Y')
                    )
                )];
            }
        }

        $info = $this->routeAccess->get(ActionRouteMap::RootHelp, $roles);
        if ($info) {
            $buttons[] = [$this->buttonResponder->generate(actionInfo: $info)];
        }

        return $buttons;
    }
}
