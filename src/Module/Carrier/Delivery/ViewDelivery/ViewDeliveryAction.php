<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\ViewDelivery;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Carrier\Delivery\Domain\Delivery;
use CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository\DeliveryRepository;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewDeliveryAction implements ActionInterface
{
    /**
     * @param array<non-empty-string, non-empty-string> $volunteers
     */
    public function __construct(
        private NormalizerInterface $normalizer,
        private DeliveryRepository $repository,
        private array $volunteers,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Информация о доставке',
            ActionRouteMap::DeliveryView,
            [UserRole::User]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $conversation = $message->conversation;

        /** @var Delivery|null $delivery */
        $delivery = $this->repository->findOneBy([
            'id' => (string)($message->conversation->actionRoute->query['id'] ?? Uuid::nil())
        ]);

        if ($delivery) {
            $messageText = sprintf(
                "Дата доставки: %s\nВолонтёр: %s",
                $delivery->deliveryAt->format('d.m'),
                $this->volunteers[$delivery->carrierId]
            );
        } else {
            $messageText = 'Информация не найдена';
        }

        $messageContext->dispatch(new SendMessageCommand($message->userId, $messageText));

        return new Conversation($conversation->actionRoute, $this->normalizer->normalize($conversation));
    }
}
