<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\EntryPoint\BotCommand;

use CarVolunteer\Module\Telegram\MessageReceived\Application\Authorization;
use CarVolunteer\Module\Telegram\MessageReceived\Domain\CommandHandler;
use CarVolunteer\Module\Telegram\MessageReceived\Domain\TelegramMessage;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

final readonly class CreateOrderHandler implements CommandHandler
{
    public function __construct(
        private Authorization $auth,
        private BotApi $api,
    ) {
    }

    public static function getCommandName(): string
    {
        return '/create';
    }

    public function handle(TelegramMessage $message): void
    {
        if (!$this->auth->isManager($message->user)) {
            $this->api->sendMessage(
                $message->user->id,
                'Нет прав на создание заказа'
            );

            return;
        }

        $keyboard = new InlineKeyboardMarkup([
            [['text' => 'test1', 'callback_data' => '/test_1']],
            [['text' => 'test2', 'callback_data' => '/test_2']],
            [['text' => 'test3', 'callback_data' => '/test_3']],
        ]);

        $this->api->sendMessage(
            $message->user->id,
            'Начинаем создавать заказ.
                Кнопки:',
            'html',
            false,
            null,
            $keyboard
        );
    }
}
