<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\EditParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Application\UseCases\EditParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder\EditParcelTelegramResponder;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Telephantast\MessageBus\MessageContext;

final readonly class EditParcelAction implements ActionInterface
{
    public function __construct(
        private EditParcelUseCase $useCase,
        private EditParcelTelegramResponder $responder,
    ) {
    }

    public static function getRoute(): string
    {
        return '/editParcel';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $parcel = $this->useCase->handle(
            $message->userId,
            (string)($message->conversation->actionRoute->query['id'] ?? Uuid::nil()),
            $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [],
            $message->message
        );

        $commands = $this->responder->getMessages($message->userId, $parcel);
        foreach ($commands as $command) {
            $messageContext->dispatch($command);
        }

        return $message->conversation;
    }
}
