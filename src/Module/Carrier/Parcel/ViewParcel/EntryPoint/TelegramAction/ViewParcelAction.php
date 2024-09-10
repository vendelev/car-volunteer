<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases\ViewParcelUseCase;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewParcelAction implements ActionInterface
{
    public function __construct(
        private ViewParcelUseCase $viewUseCase
    ) {
    }

    public static function getRoute(): string
    {
        return '/viewParcel';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $command = $this->viewUseCase->handle(
            $message->userId,
            (string)($message->conversation->actionRoute->query['id'] ?? Uuid::nil()),
            $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [],
        );

        if ($command !== null) {
            $messageContext->dispatch($command);
        }

        return $message->conversation;
    }
}
