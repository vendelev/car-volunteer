<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\EditParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Application\EditParcelPlayLoadFactory;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Application\UseCases\EditParcelUseCase;
use Telephantast\MessageBus\MessageContext;

final readonly class EditParcelTitleAction implements ActionInterface
{
    public function __construct(
        private EditParcelPlayLoadFactory $playLoadFactory,
        private EditParcelUseCase $useCase,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Изменить название',
            ActionRouteMap::ParcelEditTitle,
            [UserRole::Manager, UserRole::Receiver]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $playLoad = $this->playLoadFactory->createFromMessage($message);
        $roles = $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [];
        $commands = $this->useCase->editTitle(
            userId: $message->userId,
            playLoad: $playLoad,
            roles: $roles
        );

        foreach ($commands as $command) {
            $messageContext->dispatch($command);
        }

        return new Conversation($message->conversation->actionRoute, (array)$playLoad);
    }
}
