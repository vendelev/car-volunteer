<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\BaseAction\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Infrastructure\Telegram\ActionLocator;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class HelpAction implements ActionInterface
{
    public function __construct(
        private ActionLocator $actionLocator,
    ) {
    }

    public static function getRoute(): string
    {
        return '/help';
    }

    public static function getTitle(): ?string
    {
        return 'Помощь';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $routes = $this->actionLocator->getAll();
        $buttons = [];

        foreach ($routes as $route => $className) {
            if ($className::getTitle()) {
                $buttons[] = ['text' => $className::getTitle(), 'callback_data' => $route];
            }
        }

        $messageContext->dispatch(new SendMessageCommand(
            $message->userId,
            'Список доступных действий',
            new InlineKeyboardMarkup([$buttons])
        ));

        return new Conversation(self::getRoute());
    }
}
