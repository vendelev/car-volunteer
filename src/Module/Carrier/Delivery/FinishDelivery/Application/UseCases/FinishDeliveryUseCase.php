<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\FinishDelivery\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Delivery\Domain\Delivery;
use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use CarVolunteer\Module\Carrier\Delivery\FinishDelivery\Domain\FinishDeliveryPlayLoad;
use CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository\DeliveryRepository;
use CarVolunteer\Module\Carrier\Domain\ParcelDeliveredEvent;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageBus;

final readonly class FinishDeliveryUseCase
{
    public function __construct(
        private MessageBus $messageBus,
        private DeliveryRepository $repository,
    ) {
    }

    public function handle(string $userId, FinishDeliveryPlayLoad $playLoad, bool $confirm): FinishDeliveryPlayLoad
    {
        /** @var Delivery|null $entity */
        $entity = $this->repository->findOneBy(['id' => $playLoad->parcelId]);

        if ($entity === null || $entity->status !== DeliveryStatus::WaitDelivery->value) {
            return $playLoad;
        }

        if (!$confirm) {
            $this->messageBus->dispatch(new SendMessageCommand(
                $userId,
                'Подтвердите, что посылка доставлена',
                new InlineKeyboardMarkup([
                    [['text' => 'Доставлено', 'callback_data' => '/finishDelivery?confirm=1']],
                    [['text' => 'Отмена', 'callback_data' => '/viewParcel?id=' . $playLoad->parcelId]],
                ])
            ));
        } else {
            $this->messageBus->dispatch(new ParcelDeliveredEvent($playLoad->parcelId, $entity->id));
            $this->messageBus->dispatch(new SendMessageCommand(
                $userId,
                '💥Спасибо за доставку💥',
                new InlineKeyboardMarkup([
                    [['text' => 'Список посылок', 'callback_data' => '/viewParcels']],
                    [['text' => 'Помощь', 'callback_data' => '/help']],
                ])
            ));
        }

        return $playLoad;
    }
}