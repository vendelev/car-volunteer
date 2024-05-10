<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\EntryPoint\MessageCommand;

use CarVolunteer\Domain\Event\UserEnroled;
use CarVolunteer\Module\Telegram\Repository\UserRepository;
use TelegramBot\Api\BotApi;

final readonly class RegisterUserCommand
{
    public function __construct(
        private UserRepository $userRepository,
        private BotApi $api,
    ) {
    }
    public function __invoke(UserEnroled $message): void
    {
        $dbUser = $this->userRepository->find($message->user->id);

        if ($dbUser === null) {
            $this->userRepository->save($message->user);
            $this->api->sendMessage($message->user->id, 'Вы зарегистрированы, обратитесь к админу для добавления роли');
        }
    }
}