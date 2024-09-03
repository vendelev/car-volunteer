<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction;

use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\RootActionInterface;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Module\Telegram\Domain\UserJoinedEvent;
use CarVolunteer\Module\Telegram\Domain\UserAttribute;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class StartAction implements RootActionInterface
{
    public static function getRoute(): string
    {
        return '/start';
    }

    public static function getTitle(): string
    {
        return 'Начало';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $messageContext->dispatch(
            new SendMessageCommand(
                $message->userId,
                sprintf('Здравствуйте, для продолжения нажмите кнопку "%s"', HelpAction::getTitle()),
                new InlineKeyboardMarkup([
                    [['text' => HelpAction::getTitle(), 'callback_data' => HelpAction::getRoute()]],
                ])
            )
        );

        $user = $messageContext->getAttribute(UserAttribute::class);
        if ($user?->user !== null) {
            $messageContext->dispatch(new UserJoinedEvent($user?->user));
        }

        return $message->conversation;
    }
}