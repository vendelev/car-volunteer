<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\BotCommand;

use CarVolunteer\Domain\UserEnroledEvent;
use CarVolunteer\Module\Telegram\MessageReceived\Application\CommandLocator;
use CarVolunteer\Module\Telegram\MessageReceived\Domain\CommandHandler;
use CarVolunteer\Module\Telegram\MessageReceived\Domain\TelegramMessage;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class StartHandler implements CommandHandler
{
    public function __construct(
        private CommandLocator $commandLocator,
        private MessageBusInterface $messageBus,
    ) {
    }

    public static function getCommandName(): string
    {
        return '/start';
    }

    public function handle(TelegramMessage $message): void
    {
        $this->messageBus->dispatch(new UserEnroledEvent($message->user));
        $this->commandLocator->get(HelpHandler::getCommandName())?->handle($message);
    }
}
