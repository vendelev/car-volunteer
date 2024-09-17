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
use Telephantast\MessageBus\MessageContext;

final readonly class ViewParcelsAction implements ActionInterface
{
    public function __construct(
        private ViewParcelUseCase $listUseCase,
        private ViewParcelTelegramResponder $presenter,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Список посылок',
            ActionRouteMap::ParcelList,
            [UserRole::User]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $roles = $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [];
        $models = $this->listUseCase->getListActiveParcels();
        $command = $this->presenter->viewActiveParcels($message->userId, $models, $roles);
        $messageContext->dispatch($command);

        return $message->conversation;
    }
}
