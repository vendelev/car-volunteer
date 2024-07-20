<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\BaseAction\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionHandler;
use CarVolunteer\Domain\Conversation\StartNewConversationCommand;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Module\Telegram\ReceiveMessage\Domain\User;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class StartAction implements ActionHandler
{
    public static function getActionName(): string
    {
        return '/start';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): void
    {
        /** @var User $userStamp */
        $userStamp = $messageContext->getStamp(User::class);
        $messageContext->dispatch(
            new SendMessageCommand(
                $userStamp->id,
                'Здравствуйте, для продолжения нажмите кнопку "Помощь"',
                new InlineKeyboardMarkup([
                    [['text' => 'Помощь', 'callback_data' => HelpAction::getActionName()]],
                ])
            )
        );
        $messageContext->dispatch(new StartNewConversationCommand(self::getActionName()));
//        $messageContext->dispatch(new UserEnroledEvent($userStamp));
    }
}
