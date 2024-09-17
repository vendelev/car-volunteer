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
use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application\ParcelPlayLoadFactory;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Application\UseCases\EditParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder\EditParcelTelegramResponder;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\MessageBus\MessageContext;

final readonly class EditParcelAction implements ActionInterface
{
    public function __construct(
        private EditParcelUseCase $useCase,
        private EditParcelTelegramResponder $responder,
        private ParcelPlayLoadFactory $playLoadFactory,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Изменить описание посылки',
            ActionRouteMap::ParcelEditDescription,
            [UserRole::Manager, UserRole::Receiver]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $conversation = $message->conversation;
        $parcelId = (string)($message->conversation->actionRoute->query['id'] ?? Uuid::nil());

        /** @var array{id: string, status: string, description?: string} $playLoadData */
        $playLoadData = $conversation->playLoad;
        if (empty($playLoadData['id']) || $playLoadData['id'] !== $parcelId) {
            $playLoadData = ['id' => $parcelId, 'status' => ParcelStatus::New->value];
        }

        $playLoad = $this->playLoadFactory->createFromConversation($playLoadData);
        $roles = $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [];
        $parcel = $this->useCase->handle($message->userId, $playLoad, $roles, $message->message);

        $commands = $this->responder->getEditMessages($message->userId, $parcel, $roles);
        foreach ($commands as $command) {
            $messageContext->dispatch($command);
        }

        return new Conversation($conversation->actionRoute, $this->playLoadFactory->toArray($playLoad));
    }
}
