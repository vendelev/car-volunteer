<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases\ViewParcelsUseCase;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewParcelsAction implements ActionInterface
{
    public function __construct(
        private ViewParcelsUseCase $listUseCase
    ) {
    }

    public static function getRoute(): string
    {
        return '/viewParcels';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $this->listUseCase->handle($message->userId, $messageContext);

        return $message->conversation;
    }
}