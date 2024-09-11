<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\Infrastructure\Presenter;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Domain\ViewParcelModel;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Domain\ViewParcelActions;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class ViewParcelTelegramPresenter
{
    /**
     * Создание сообщения показа одного заказ-наряда
     */
    public function viewParcel(string $userId, ViewParcelModel $model): SendMessageCommand
    {
        $buttons = [];
        $parcelId = $model->parcel->id;

        foreach ($model->actions as $action) {
            switch ($action) {
                case ViewParcelActions::EditParcel:
                    $buttons[] = [
                        'text' => 'Редактировать описание',
                        'callback_data' => '/editParcel?parcelId=' . $parcelId
                    ];
                    break;
                case ViewParcelActions::PackParcel:
                    $buttons[] = ['text' => 'Собрать посылку', 'callback_data' => '/packParcel?parcelId=' . $parcelId];
                    break;
                case ViewParcelActions::CreateDelivery:
                    $buttons[] = [
                        'text' => 'Создать доставку',
                        'callback_data' => '/createDelivery?parcelId=' . $parcelId
                    ];
                    break;
                case ViewParcelActions::FinishDelivery:
                    $buttons[] = [
                        'text' => 'Завершить доставку',
                        'callback_data' => '/finishDelivery?parcelId=' . $parcelId
                    ];
                    break;
            }
        }

        $buttons[] = ['text' => 'Отмена', 'callback_data' => '/viewParcels'];
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
     * Создание сообщения для вывода списка заказ-нарядов
     *
     * @param list<Parcel> $list
     */
    public function viewParcels(string $userId, array $list): SendMessageCommand
    {
        foreach ($list as $item) {
            $buttons[] = [[
                'text' => sprintf(
                    '%s%s%s %s (от %s)',
                    ($item->status === ParcelStatus::Delivered->value ? '☑' : ''),
                    ($item->packingId ? 'ⓟ' : ''),
                    ($item->deliveryId ? 'ⓓ' : ''),
                    $item->title,
                    $item->createAt->format('d.m.Y')
                ),
                'callback_data' => '/viewParcel?id=' . $item->id
            ]];
        }

        $buttons[] = [['text' => 'Помощь', 'callback_data' => '/help']];

        return new SendMessageCommand(
            $userId,
            'Список активных посылок',
            new InlineKeyboardMarkup($buttons)
        );
    }
}
