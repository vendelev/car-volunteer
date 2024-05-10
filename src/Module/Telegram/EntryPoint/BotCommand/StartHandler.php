<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\EntryPoint\BotCommand;

use CarVolunteer\Module\Telegram\Application\CommandLocator;
use CarVolunteer\Module\Telegram\Domain\CommandHandler;
use CarVolunteer\Module\Telegram\Domain\TelegramMessage;
use CarVolunteer\Repository\UserRepository;
use TelegramBot\Api\BotApi;

final readonly class StartHandler implements CommandHandler
{
    public function __construct(
        private BotApi $api,
        private UserRepository $userRepository,
        private CommandLocator $commandLocator,
    ) {
    }

    public static function getCommandName(): string
    {
        return '/start';
    }

    public function handle(TelegramMessage $message): void
    {
        $dbUser = $this->userRepository->find($message->user->id);

        if ($dbUser === null) {
            $this->userRepository->save($message);
            $this->api->sendMessage($message->user->id, 'Вы зарегистрированы, обратитесь к админу для добавления роли');
        }

        //$this->commandLocator->get(HelpHandler::getCommandName())?->handle($message);
    }
}
