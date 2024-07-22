<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\User\EntryPoint\MessageCommand;

use CarVolunteer\Module\Telegram\User\Repository\UserRepository;
use TelegramBot\Api\BotApi;

final readonly class RegisterUserCommand
{
    public function __construct(
        private UserRepository $userRepository,
        private BotApi $api,
    ) {
    }
//    public function __invoke($message): void
//    {
//        $dbUser = $this->userRepository->find($message->user->id);
//
//        if ($dbUser === null) {
//            $this->userRepository->save($message->user);
//            $this->api->sendMessage($message->user->id, 'Вы зарегистрированы, обратитесь к админу для добавления роли');
//        }
//    }
}