<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Module\Carrier\Packing\Application\PackPlayLoadFactory;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\CreatePackUseCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Telephantast\MessageBus\MessageContext;

final readonly class CreatePackAction implements ActionInterface
{
    public function __construct(
        private NormalizerInterface $normalizer,
        private PackPlayLoadFactory $packFactory,
        private CreatePackUseCase   $packUseCase,
    ) {
    }

    public static function getRoute(): string
    {
        return '/packParcel';
    }

    public static function getTitle(): ?string
    {
        return 'Собрать посылку';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $conversation = $message->conversation;
        $playLoad = $this->packFactory->createFromConversation(
            $conversation->actionRoute->query['parcelId'] ?? '',
            $conversation->playLoad
        );

        $packing = $this->packUseCase->handle(
            $message->userId,
            $playLoad,
            filter_var($conversation->actionRoute->query['confirm'] ?? '', FILTER_VALIDATE_BOOL),
            $messageContext
        );

        return new Conversation($conversation->actionRoute, $this->normalizer->normalize($packing));
    }
}