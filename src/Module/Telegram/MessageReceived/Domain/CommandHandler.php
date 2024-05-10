<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\MessageReceived\Domain;

interface CommandHandler
{
    public static function getCommandName(): string;

    public function handle(TelegramMessage $message): void;
}
