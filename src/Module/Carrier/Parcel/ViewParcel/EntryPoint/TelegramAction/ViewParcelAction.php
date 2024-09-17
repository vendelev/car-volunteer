<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases\ViewParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Infrastructure\Responder\ViewParcelTelegramResponder;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewParcelAction implements ActionInterface
{
    public function __construct(
        private ViewParcelUseCase $viewUseCase,
        private ViewParcelTelegramResponder $responder,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Посмотреть описание посылки',
            ActionRouteMap::ParcelView,
            [UserRole::User]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $model = $this->viewUseCase->getViewParcel(
            $message->userId,
            (string)($message->conversation->actionRoute->query['id'] ?? Uuid::nil()),
            $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [],
        );

        if ($model !== null) {
            $command = $this->responder->viewParcel($message->userId, $model);
            $messageContext->dispatch($command);
        }

        return $message->conversation;
    }
}
