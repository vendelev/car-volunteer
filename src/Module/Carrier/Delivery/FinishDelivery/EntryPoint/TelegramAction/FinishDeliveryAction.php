<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\FinishDelivery\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Carrier\Delivery\FinishDelivery\Application\FinishDeliveryPlayLoadFactory;
use CarVolunteer\Module\Carrier\Delivery\FinishDelivery\Application\UseCases\FinishDeliveryUseCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Telephantast\MessageBus\MessageContext;

final readonly class FinishDeliveryAction implements ActionInterface
{
    public function __construct(
        private NormalizerInterface $normalizer,
        private FinishDeliveryPlayLoadFactory $playLoadFactory,
        private FinishDeliveryUseCase $deliveryUseCase,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Завершить доставку',
            ActionRouteMap::DeliveryFinish,
            [UserRole::Volunteer]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $conversation = $message->conversation;
        $playLoad = $this->playLoadFactory->createFromConversation(
            $conversation->actionRoute->query['parcelId'] ?? '',
            $conversation->playLoad
        );

        $result = $this->deliveryUseCase->handle(
            $message->userId,
            $playLoad,
            filter_var($conversation->actionRoute->query['confirm'] ?? '', FILTER_VALIDATE_BOOL),
        );

        return new Conversation($conversation->actionRoute, $this->normalizer->normalize($result));
    }
}
