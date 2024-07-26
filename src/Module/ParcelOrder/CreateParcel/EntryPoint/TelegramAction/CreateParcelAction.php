<?php

declare(strict_types=1);

namespace CarVolunteer\Module\ParcelOrder\CreateParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Module\ParcelOrder\CreateParcel\Application\UseCases\CreateParcelUseCase;
use CarVolunteer\Module\ParcelOrder\Domain\Dto\Parcel;
use CarVolunteer\Module\ParcelOrder\Domain\ParcelStatus;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Telephantast\MessageBus\MessageContext;

/**
 * Заказ-наряд на посылку
 */
final readonly class CreateParcelAction implements ActionInterface
{
    public function __construct(
        private NormalizerInterface $normalizer,
        private DenormalizerInterface $denormalizer,
        private CreateParcelUseCase $parcelUseCase,
    ) {
    }

    public static function getRoute(): string
    {
        return '/createParcel';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $selfRoute = self::getRoute();

        $conversation = $message->conversation;
        if ($conversation?->actionRoute !== $selfRoute) {
            $conversation = new Conversation($selfRoute);
        }

        $playLoad = $conversation->playLoad;
        if (empty($playLoad)) {
            $playLoad = new Parcel(
                id: Uuid::v7(),
                status: ParcelStatus::New
            );
        } else {
            $playLoad = $this->denormalizer->denormalize($playLoad, Parcel::class);
        }

        $parcel = $this->parcelUseCase->handle($message->userId, $playLoad, $message->message, $messageContext);

        return new Conversation($selfRoute, $this->normalizer->normalize($parcel));
    }
}