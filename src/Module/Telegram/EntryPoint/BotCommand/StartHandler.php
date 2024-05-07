<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\EntryPoint\BotCommand;

use CarVolunteer\Domain\TelegramMessage;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class StartHandler
{
    public function __construct(
        private BotApi $api,
    ) {
    }

    public function getCommandName(): string
    {
        return '/start';
    }

    public function __invoke(TelegramMessage $message): void
    {
        if ($message->command !== $this->getCommandName()) {
            return;
        }

        $keyboard = new InlineKeyboardMarkup([
            [['text' => 'Создать заказ', 'callback_data' => '/create']],
        ]);

        $this->api->sendMessage(
            $message->user->id,
            'Выберите действие:',
            'markdown',
            false,
            null,
            $keyboard
        );
    }
}
