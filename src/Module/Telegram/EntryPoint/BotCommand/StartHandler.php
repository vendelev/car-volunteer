<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\EntryPoint\BotCommand;

use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Repository\UserRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use TelegramBot\Api\BotApi;

final readonly class StartHandler
{
    public function __construct(
        private BotApi $api,
        private UserRepository $userRepository,
        private MessageBusInterface $bus
    ) {
    }

    public static function getCommandName(): string
    {
        return '/start';
    }

    public function __invoke(TelegramMessage $message): void
    {
        if ($message->command !== self::getCommandName()) {
            return;
        }

        $dbUser = $this->userRepository->find($message->user->id);

        if ($dbUser === null) {
            $this->userRepository->save($message);
            $this->api->sendMessage($message->user->id, 'Вы зарегистрированы, обратитесь к админу для добавления роли');
        }

        $message->command = HelpHandler::getCommandName();
        $this->bus->dispatch($message);
    }
}
