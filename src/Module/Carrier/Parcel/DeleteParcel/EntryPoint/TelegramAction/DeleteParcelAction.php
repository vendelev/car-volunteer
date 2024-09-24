<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\DeleteParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Carrier\Parcel\DeleteParcel\Application\DeletePlayLoadFactory;
use CarVolunteer\Module\Carrier\Parcel\DeleteParcel\Infrastructure\Responder\DeleteParcelTelegramResponder;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelDeletedEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\Infrastructure\Repository\ParcelRepository;
use Telephantast\MessageBus\MessageContext;

final readonly class DeleteParcelAction implements ActionInterface
{
    public function __construct(
        private ParcelRepository $repository,
        private DeletePlayLoadFactory $playLoadFactory,
        private ActionRouteAccess $routeAccess,
        private DeleteParcelTelegramResponder $responder,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Удалить посылку',
            ActionRouteMap::ParcelDelete,
            [UserRole::Manager]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $conversation = $message->conversation;
        $playLoad = $this->playLoadFactory->createFromConversation($conversation);

        $buttons = null;
        $roles = $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [];
        $parcel = $this->repository->findOneBy(['id' => $playLoad->id]);

        if (
            $parcel
            && $parcel->status !== ParcelStatus::Delivered->value
            && $this->routeAccess->can(self::getInfo()->accessRoles, $roles)
        ) {
            if (!$playLoad->confirm) {
                $messageText = 'Нажмите кнопку "Подтверждаю" для удаления';
                $buttons = $this->responder->getBeforeDeleteButtons(self::getInfo(), $roles);
            } else {
                $messageText = 'Посылка удалена';
                $parcel->status = ParcelStatus::Deleted->value;
                $messageContext->dispatch(new ParcelDeletedEvent($parcel));
                $buttons = $this->responder->getAfterDeleteButtons($roles);
            }
        } else {
            $messageText = 'Удаление не возможно';
        }

        $messageContext->dispatch(new SendMessageCommand($message->userId, $messageText, $buttons));

        return new Conversation($conversation->actionRoute, (array)$playLoad);
    }
}
