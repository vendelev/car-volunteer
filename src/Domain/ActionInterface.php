<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

use CarVolunteer\Domain\Conversation\Conversation;
use Telephantast\MessageBus\MessageContext;

interface ActionInterface
{
    public static function getRoute(): string;

    public static function getTitle(): ?string;

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation;
}
