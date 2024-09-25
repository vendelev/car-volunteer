<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\ViewDelivery;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Infrastructure\Telegram\RouteParam;
use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository\DeliveryRepository;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewDeliveryAction implements ActionInterface
{
    /**
     * @param array<non-empty-string, non-empty-string> $volunteers
     */
    public function __construct(
        private DeliveryRepository $repository,
        private array $volunteers,
        private ButtonResponder $buttonResponder,
        private ActionRouteAccess $routeAccess,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Информация о доставке',
            ActionRouteMap::DeliveryView,
            [UserRole::User]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $conversation = $message->conversation;
        $id = (string)($message->conversation->actionRoute->query['id'] ?? Uuid::nil());

        $delivery = $this->repository->findOneBy(['id' => $id]);
        $buttons = [];

        if ($delivery) {
            $messageText = sprintf(
                "Дата доставки: %s\nВолонтёр: %s",
                $delivery->deliveryAt->format('d.m'),
                $this->volunteers[$delivery->carrierId]
            );

            $roles = $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [];

            if ($delivery->status !== DeliveryStatus::Delivered->value) {
                $info = $this->routeAccess->get(ActionRouteMap::DeliveryDelete, $roles);
                if ($info) {
                    $buttons[] = [$this->buttonResponder->generate(
                        actionInfo: $info,
                        param: new RouteParam('id', $delivery->id->toString())
                    )];
                }
            }
        } else {
            $messageText = 'Информация не найдена';
        }

        $messageContext->dispatch(new SendMessageCommand(
            $message->userId,
            $messageText,
            $buttons ? new InlineKeyboardMarkup($buttons) : null,
        ));

        return new Conversation($conversation->actionRoute, ['id' => $id]);
    }
}
