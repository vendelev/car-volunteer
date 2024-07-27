<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\ViewParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Module\Carrier\ViewParcel\Application\UseCases\ViewParcelUseCase;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewParcelAction implements ActionInterface
{
    public function __construct(
        private ViewParcelUseCase $listUseCase
    ) {
    }

    public static function getRoute(): string
    {
        return '/viewParcel';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $this->listUseCase->handle(
            $message->userId,
            $message->conversation->actionRoute->query['id'] ?? 0,
            $messageContext
        );

        return $message->conversation;
    }
}