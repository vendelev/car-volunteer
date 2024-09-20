<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Carrier\Packing\Application\PackPlayLoadFactory;
use CarVolunteer\Module\Carrier\Packing\Application\UseCases\CreatePackUseCase;
use CarVolunteer\Module\Carrier\Packing\Domain\UserClickEvent;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder\CreatePackTelegramResponder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Telephantast\MessageBus\MessageContext;

final readonly class CreatePackAction implements ActionInterface
{
    public function __construct(
        private NormalizerInterface $normalizer,
        private PackPlayLoadFactory $packFactory,
        private CreatePackUseCase $packUseCase,
        private CreatePackTelegramResponder $responder,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Собрать посылку',
            ActionRouteMap::PackParcel,
            [UserRole::Manager, UserRole::Picker, UserRole::Receiver]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $conversation = $message->conversation;
        $playLoad = $this->packFactory->createFromConversation(
            (string)($conversation->actionRoute->query['parcelId'] ?? ''),
            $conversation->playLoad
        );

        $want = (string)($conversation->actionRoute->query['want'] ?? '');
        $clickEvent = $want !== '' ? UserClickEvent::from($want) : null;
        $packing = $this->packUseCase->handle(
            $message->userId,
            $playLoad,
            $clickEvent,
            $message->photoId,
        );

        $roles = $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [];
        $commands = $this->responder->createPack($message->userId, $packing->status, $packing->parcelId, $roles);
        foreach ($commands as $command) {
            $messageContext->dispatch($command);
        }

        return new Conversation($conversation->actionRoute, (array)$this->normalizer->normalize($packing));
    }
}
