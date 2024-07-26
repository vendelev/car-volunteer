<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction;

use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\RootActionInterface;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class CreateAction implements RootActionInterface
{
    public static function getRoute(): string
    {
        return '/create';
    }

    public static function getTitle(): string
    {
        return 'Создать';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $messageContext->dispatch(new SendMessageCommand(
            $message->userId,
            'Список доступных действий:',
            new InlineKeyboardMarkup([
                //todo переделать на interface CreateActionInterface
                [['text' => 'Создать заказ-наряд на посылку', 'callback_data' => '/createParcel']],
            ])
        ));

        return new Conversation(self::getRoute());
    }
}
