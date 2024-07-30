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
            'Вы можете создать:',
            new InlineKeyboardMarkup([
                //todo переделать на interface CreateActionInterface
                [['text' => 'Заказ-наряд на посылку', 'callback_data' => '/createParcel']],
                [['text' => 'Отмена', 'callback_data' => '/help']],
            ])
        ));

        return $message->conversation;
    }
}
