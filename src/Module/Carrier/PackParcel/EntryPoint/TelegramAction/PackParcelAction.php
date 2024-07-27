<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\PackParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use Telephantast\MessageBus\MessageContext;

final class PackParcelAction implements ActionInterface
{
    public static function getRoute(): string
    {
        return '/pack';
    }

    public static function getTitle(): ?string
    {
        return 'Упаковать посылку';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        return $message->conversation;
    }
}