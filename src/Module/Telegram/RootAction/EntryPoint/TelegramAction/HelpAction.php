<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction;

use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\RootActionInterface;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class HelpAction implements RootActionInterface
{
    public static function getRoute(): string
    {
        return '/help';
    }

    public static function getTitle(): string
    {
        return 'Помощь';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $messageContext->dispatch(new SendMessageCommand(
            $message->userId,
            'Список доступных действий:',
            new InlineKeyboardMarkup([
                [['text' => CreateAction::getTitle(), 'callback_data' => CreateAction::getRoute()]],
                [['text' => ViewAction::getTitle(), 'callback_data' => ViewAction::getRoute()]],
                [['text' => self::getTitle(), 'callback_data' => self::getRoute()]],
            ])
        ));

        return new Conversation(self::getRoute());
    }
}
