<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\DeleteDelivery\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Carrier\Application\DeletePlayLoadFactory;
use CarVolunteer\Module\Carrier\Delivery\DeleteDelivery\Infrastructure\Responder\DeleteDeliveryTelegramResponder;
use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository\DeliveryRepository;
use CarVolunteer\Module\Carrier\Domain\DeliveryDeletedEvent;
use Telephantast\MessageBus\MessageContext;

final readonly class DeleteDeliveryAction implements ActionInterface
{
    public function __construct(
        private DeliveryRepository $repository,
        private DeletePlayLoadFactory $playLoadFactory,
        private ActionRouteAccess $routeAccess,
        private DeleteDeliveryTelegramResponder $responder,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Отменить доставку',
            ActionRouteMap::DeliveryDelete,
            [UserRole::Manager]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $conversation = $message->conversation;
        $playLoad = $this->playLoadFactory->createFromConversation($conversation);

        $buttons = null;
        $roles = $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [];
        $delivery = $this->repository->findOneBy(['id' => $playLoad->id]);

        if (
            $delivery
            && $delivery->status !== DeliveryStatus::Delivered->value
            && $this->routeAccess->can(self::getInfo()->accessRoles, $roles)
        ) {
            if (!$playLoad->confirm) {
                $messageText = 'Нажмите кнопку "Подтверждаю" для удаления';
                $buttons = $this->responder->getBeforeDeleteButtons(self::getInfo(), $roles, $delivery->parcelId);
            } else {
                $messageText = 'Доставка отменена';
                $messageContext->dispatch(new DeliveryDeletedEvent(
                    deliveryId: $delivery->id,
                    parcelId: $delivery->parcelId
                ));
                $buttons = $this->responder->getAfterDeleteButtons($roles, $delivery->parcelId);
            }
        } else {
            $messageText = 'Удаление не возможно';
        }

        $messageContext->dispatch(new SendMessageCommand($message->userId, $messageText, $buttons));

        return new Conversation($conversation->actionRoute, (array)$playLoad);
    }
}
