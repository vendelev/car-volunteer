<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Domain\DeliveryPlayLoad;
use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository\DeliveryRepository;
use CarVolunteer\Module\Carrier\Domain\DeliveryCreatedEvent;
use DateTimeImmutable;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageBus;

final readonly class CreateDeliveryUseCase
{
    private string $userId;

    /**
     * @param array<non-empty-string, non-empty-string> $volunteers
     */
    public function __construct(
        private DeliveryRepository $repository,
        private MessageBus         $messageBus,
        private array              $volunteers,
    ) {
    }

    public function handle(string $userId, DeliveryPlayLoad $delivery, bool $confirm, ?string $message): DeliveryPlayLoad
    {
        $this->userId = $userId;

        $entity = $this->repository->findOneBy(['parcelId' => $delivery->parcelId]);

        if ($entity !== null) {
            $this->sendMessage(
                'Доставка уже собрана',
                new InlineKeyboardMarkup([
                    [['text' => 'Просмотр посылки', 'callback_data' => '/viewParcel?id=' . $delivery->parcelId]]
                ])
            );

            return $delivery;
        }

        if ($delivery->status === DeliveryStatus::New) {
            return $this->stepWaitDate($delivery);
        }

        if ($delivery->status === DeliveryStatus::WaitDate) {
            if (empty($message)) {
                return $this->stepWaitDate($delivery);
            }

            $result = $this->stepSetDate($delivery, $message);
            return $this->stepWaitCarrier($result);
        }

        if ($delivery->status === DeliveryStatus::WaitCarrier) {
            $result = $this->setCarrier($delivery, $message);
            return $this->stepWaitConfirm($result);
        }

        if ($delivery->status === DeliveryStatus::WaitConfirm) {
            return $this->stepSaveDelivery($delivery, $confirm);
        }

        return $delivery;
    }

    private function stepWaitDate(DeliveryPlayLoad $delivery): DeliveryPlayLoad
    {
        $delivery->status = DeliveryStatus::WaitDate;
        $date = new DateTimeImmutable();

        $this->sendMessage(
            'Выберете дату доставки: ',
            new InlineKeyboardMarkup([
                [
                    ['text' => $this->format($date), 'callback_data' => $this->format($date, 'now', false)],
                    ['text' => $this->format($date, '+1 day'), 'callback_data' => $this->format($date, '+1 day', false)],
                    ['text' => $this->format($date, '+2 day'), 'callback_data' => $this->format($date, '+2 day', false)],
                ],
                [
                    ['text' => $this->format($date, '+3 day'), 'callback_data' => $this->format($date, '+3 day', false)],
                    ['text' => $this->format($date, '+4 day'), 'callback_data' => $this->format($date, '+4 day', false)],
                    ['text' => $this->format($date, '+5 day'), 'callback_data' => $this->format($date, '+5 day', false)],
                ],
                [
                    ['text' => $this->format($date, '+6 day'), 'callback_data' => $this->format($date, '+6 day', false)],
                    ['text' => $this->format($date, '+7 day'), 'callback_data' => $this->format($date, '+7 day', false)],
                    ['text' => $this->format($date, '+8 day'), 'callback_data' => $this->format($date, '+8 day', false)],
                ],
                [['text' => 'Отмена', 'callback_data' => '/viewParcel?id=' . $delivery->parcelId]]
            ])
        );

        return $delivery;
    }

    private function stepSetDate(DeliveryPlayLoad $delivery, string $message): DeliveryPlayLoad
    {
        $delivery->deliveryDate = new DateTimeImmutable($message);

        return $delivery;
    }

    private function stepWaitCarrier(DeliveryPlayLoad $delivery): DeliveryPlayLoad
    {
        $delivery->status = DeliveryStatus::WaitCarrier;
        $buttons = [];

        foreach ($this->volunteers as $id => $name) {
            $buttons[] = [['text' => $name, 'callback_data' => $id]];
        }

        $buttons[] = [['text' => 'Отмена', 'callback_data' => '/viewParcel?id=' . $delivery->parcelId]];

        $this->sendMessage('Выберете волонтёра: ', new InlineKeyboardMarkup($buttons));

        return $delivery;
    }

    private function setCarrier(DeliveryPlayLoad $delivery, string $message): DeliveryPlayLoad
    {
        $delivery->status = DeliveryStatus::WaitDelivery;
        $delivery->carrierId = $message;

        return $delivery;
    }

    private function stepWaitConfirm(DeliveryPlayLoad $delivery): DeliveryPlayLoad
    {
        $delivery->status = DeliveryStatus::WaitConfirm;

        $this->sendMessage(
            sprintf(
                "Дата доставки: %s\nВолонтёр: %s\nПодтвердите, что всё верно",
                $this->format($delivery->deliveryDate),
                $this->volunteers[$delivery->carrierId]
            ),
            new InlineKeyboardMarkup([
                [['text' => 'Всё верно', 'callback_data' => '/createDelivery?confirm=1']],
                [['text' => 'Отмена', 'callback_data' => '/viewParcels']],
            ])
        );

        return $delivery;
    }

    private function stepSaveDelivery(DeliveryPlayLoad $delivery, bool $confirm): DeliveryPlayLoad
    {
        if (!$confirm) {
            return $this->stepWaitConfirm($delivery);
        }

        $delivery->status = DeliveryStatus::WaitDelivery;

        $this->sendMessage(
            '✅ Информация о доставке сохранена',
            new InlineKeyboardMarkup([
                [['text' => 'Список посылок', 'callback_data' => '/viewParcels']],
                [['text' => 'Помощь', 'callback_data' => '/help']],
            ])
        );

        $this->messageBus->dispatch(new DeliveryCreatedEvent($delivery->carrierId, $delivery->parcelId, $delivery->id));

        return $delivery;
    }

    private function format(DateTimeImmutable $date, string $modifier = 'now', bool $isShot = true): string
    {
        $format = $isShot ? 'd.m' : 'Y-m-d';

        return $date->modify($modifier)->format($format);
    }

    private function sendMessage(string $text, ?InlineKeyboardMarkup $buttons = null): void
    {
        $this->messageBus->dispatch(new SendMessageCommand($this->userId, $text, $buttons));
    }
}