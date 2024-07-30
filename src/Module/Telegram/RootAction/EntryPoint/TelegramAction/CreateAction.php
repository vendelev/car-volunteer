<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction;

use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\RootActionInterface;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Domain\User\UserRole;
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
        $auth = $messageContext->getAttribute(AuthorizeAttribute::class);
        $roles = $auth->roles ?? [];

        //todo переделать на interface CreateActionInterface
        $buttons = [];

        if (in_array(UserRole::Manager, $roles, true)
            || in_array(UserRole::Recipient, $roles, true)) {
            $buttons[] = [['text' => 'Заказ-наряд на посылку', 'callback_data' => '/createParcel']];
        }

        $buttons[] = [['text' => 'Отмена', 'callback_data' => '/help']];

        $messageContext->dispatch(new SendMessageCommand(
            $message->userId,
            'Вы можете создать:',
            new InlineKeyboardMarkup($buttons)
        ));

        return $message->conversation;
    }
}
