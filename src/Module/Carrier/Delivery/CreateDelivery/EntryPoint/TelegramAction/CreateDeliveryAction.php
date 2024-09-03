<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\CreateDelivery\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\DeliveryPlayLoadFactory;
use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application\UseCases\CreateDeliveryUseCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Telephantast\MessageBus\MessageContext;

final readonly class CreateDeliveryAction implements ActionInterface
{
    public function __construct(
        private NormalizerInterface $normalizer,
        private DeliveryPlayLoadFactory $playLoadFactory,
        private CreateDeliveryUseCase $deliveryUseCase,
    ) {
    }

    public static function getRoute(): string
    {
        return '/createDelivery';
    }

    public static function getTitle(): ?string
    {
        return 'Создать доставку';
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
            $message->message,
        );

        return new Conversation($conversation->actionRoute, $this->normalizer->normalize($result));
    }
}