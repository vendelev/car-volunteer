<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Photo\GetAllPhotoQuery;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Carrier\Domain\CheckParcelDeliveredQuery;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Repository\PackingRepository;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder\ViewPackingPhotoTelegramResponder;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewPackingPhotoAction implements ActionInterface
{
    public function __construct(
        private ViewPackingPhotoTelegramResponder $responder,
        private PackingRepository $repository,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Посмотреть фото упакованной посылки',
            ActionRouteMap::PackingPhoto,
            [UserRole::User]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $packing = $this->repository->findOneBy([
            'id' => (string)($message->conversation->actionRoute->query['id'] ?? Uuid::nil())
        ]);
        $roles = $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [];

        /** @var list<string> $photoIds */
        $photoIds = !empty($packing) ? $messageContext->dispatch(new GetAllPhotoQuery($packing->id)) : [];

        if (!empty($packing?->parcelId)) {
            $isDelivery = $messageContext->dispatch(new CheckParcelDeliveredQuery($packing->parcelId));
        } else {
            $isDelivery = false;
        }

        $commands = $this->responder->viewPhoto($packing?->parcelId, $message->userId, $photoIds, $isDelivery, $roles);
        foreach ($commands as $command) {
            $messageContext->dispatch($command);
        }

        return $message->conversation;
    }
}
