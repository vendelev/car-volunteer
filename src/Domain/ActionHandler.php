<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

use Telephantast\MessageBus\MessageContext;

interface ActionHandler
{
    public static function getActionName(): string;

    public function handle(TelegramMessage $message, MessageContext $messageContext): void;
}
