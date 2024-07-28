<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\CreateParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\ParcelPlayLoadFactory;
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\UseCases\CreateParcelUseCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Telephantast\MessageBus\MessageContext;

/**
 * Заказ-наряд на посылку
 */
final readonly class CreateParcelAction implements ActionInterface
{
    public function __construct(
        private NormalizerInterface $normalizer,
        private ParcelPlayLoadFactory $playLoadFactory,
        private CreateParcelUseCase $parcelUseCase,
    ) {
    }

    public static function getRoute(): string
    {
        return '/createParcel';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $conversation = $message->conversation;
        $playLoad = $this->playLoadFactory->createFromConversation($conversation->playLoad);
        $parcel = $this->parcelUseCase->handle($message->userId, $playLoad, $message->message, $messageContext);

        return new Conversation($conversation->actionRoute, $this->normalizer->normalize($parcel));
    }
}