<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction;

use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\RootActionInterface;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewAction implements RootActionInterface
{
    public static function getRoute(): string
    {
        return '/view';
    }

    public static function getTitle(): string
    {
        return 'Посмотреть';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $messageContext->dispatch(new SendMessageCommand(
            $message->userId,
            'Список доступных действий:'
        ));

        return new Conversation(self::getRoute());
    }
}
