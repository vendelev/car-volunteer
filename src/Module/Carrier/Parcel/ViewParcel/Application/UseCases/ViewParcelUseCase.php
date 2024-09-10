<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewParcelUseCase
{
    public function __construct(
        private ParcelRepositoryInterface $parcelRepository
    ) {
    }

    public function handle(string $userId, string $parcelId, MessageContext $messageContext): void
    {
        /** @var Parcel|null $item */
        $item = $this->parcelRepository->findOneBy(['id' => $parcelId]);

        if ($item === null) {
            return;
        }

        $auth = $messageContext->getAttribute(AuthorizeAttribute::class);
        $roles = $auth->roles ?? [];

        $buttons = [];
        if ($item->packingId === null && in_array(UserRole::Picker, $roles, true)) {
            $buttons[] = [['text' => 'Собрать посылку', 'callback_data' => '/packParcel?parcelId=' . $parcelId]];
        }

        if ($item->deliveryId === null && in_array(UserRole::Manager, $roles, true)) {
            $buttons[] = [['text' => 'Создать доставку', 'callback_data' => '/createDelivery?parcelId=' . $parcelId]];
        }

        if ($item->status !== ParcelStatus::Delivered->value) {
            $buttons[] = [['text' => 'Завершить доставку', 'callback_data' => '/finishDelivery?parcelId=' . $parcelId]];
        }

        $buttons[] = [['text' => 'Отмена', 'callback_data' => '/viewParcels']];

        $messageContext->dispatch(new SendMessageCommand(
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
            new InlineKeyboardMarkup($buttons)
        ));
    }
}
