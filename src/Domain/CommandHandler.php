<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

interface CommandHandler
{
    public static function getCommandName(): string;

    public function handle(TelegramMessage $message): void;
}
