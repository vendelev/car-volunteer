<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\CreateParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
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

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Создать посылку',
            ActionRouteMap::ParcelCreate,
            [UserRole::Manager, UserRole::Receiver]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $conversation = $message->conversation;
        $playLoad = $this->playLoadFactory->createFromConversation($conversation->playLoad);
        $parcel = $this->parcelUseCase->handle($message->userId, $playLoad, $message->message, $messageContext);

        return new Conversation($conversation->actionRoute, $this->normalizer->normalize($parcel));
    }
}
