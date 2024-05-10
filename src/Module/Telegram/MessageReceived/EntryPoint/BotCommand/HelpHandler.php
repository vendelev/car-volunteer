<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\BotCommand;

use CarVolunteer\Module\Telegram\MessageReceived\Domain\CommandHandler;
use CarVolunteer\Module\Telegram\MessageReceived\Domain\TelegramMessage;
use TelegramBot\Api\BotApi;

final readonly class HelpHandler implements CommandHandler
{
    public function __construct(
        private BotApi $api,
    ) {
    }

    public static function getCommandName(): string
    {
        return '/help';
    }

    public function handle(TelegramMessage $message): void
    {
        $this->api->sendMessage($message->user->id, 'Выберите действие:');
        $this->api->sendMessage($message->user->id, '/help - Справка');
        $this->api->sendMessage($message->user->id, '/create - Создание заказа');
        $this->api->sendMessage($message->user->id, '/list - Список заказов');
    }
}
