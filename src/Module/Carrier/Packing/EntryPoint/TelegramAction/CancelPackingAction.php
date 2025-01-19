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
use CarVolunteer\Module\Carrier\Domain\CancelPackingCommand;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Responder\CancelPackingTelegramResponder;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\MessageBus\MessageContext;

final readonly class CancelPackingAction implements ActionInterface
{
    public function __construct(
        private CancelPackingTelegramResponder $responder,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(
            self::class,
            'Пересобрать посылку',
            ActionRouteMap::CancelPacking,
            [UserRole::Manager, UserRole::Picker]
        );
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $conversation = $message->conversation;
        $parcelId = Uuid::fromString((string)($conversation->actionRoute->query['parcelId'] ?? ''));
        $roles = $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [];

        $messageContext->dispatch(new CancelPackingCommand($parcelId, $message->userId));

        $commands = $this->responder->cancelPacking($message->userId, $parcelId, $roles);
        foreach ($commands as $command) {
            $messageContext->dispatch($command);
        }

        return new Conversation($conversation->actionRoute);
    }
}
