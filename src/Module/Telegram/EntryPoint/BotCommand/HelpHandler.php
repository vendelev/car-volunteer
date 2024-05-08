<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\EntryPoint\BotCommand;

use CarVolunteer\Domain\TelegramMessage;
use TelegramBot\Api\BotApi;

final readonly class HelpHandler
{
    public function __construct(
        private BotApi $api,
    ) {
    }

    public static function getCommandName(): string
    {
        return '/help';
    }

    public function __invoke(TelegramMessage $message): void
    {
        if ($message->command !== self::getCommandName()) {
            return;
        }

        $this->api->sendMessage($message->user->id, 'Выберите действие:');
        $this->api->sendMessage($message->user->id, '/help - Справка');
        $this->api->sendMessage($message->user->id, '/create - Создание заказа');
        $this->api->sendMessage($message->user->id, '/list - Список заказов');
    }
}