<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\ReceiveMessage\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionHandler;
use CarVolunteer\Domain\TelegramMessage;
use Telephantast\MessageBus\MessageContext;

final readonly class HelpAction implements ActionHandler
{
    public static function getActionName(): string
    {
        return '/help';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): void
    {
    }
}
