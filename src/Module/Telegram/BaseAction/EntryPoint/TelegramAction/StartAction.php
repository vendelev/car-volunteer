<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\BaseAction\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\StartNewConversationCommand;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class StartAction implements ActionInterface
{
    public static function getRoute(): string
    {
        return '/start';
    }

    public static function getTitle(): ?string
    {
        return null;
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): void
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
        $messageContext->dispatch(new StartNewConversationCommand(self::getRoute()));
    }
}
