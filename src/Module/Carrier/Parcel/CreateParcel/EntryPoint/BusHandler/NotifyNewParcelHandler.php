<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\CreateParcel\EntryPoint\BusHandler;

use CarVolunteer\Module\Carrier\Parcel\CreateParcel\Infrastructure\Responder\NotifyTelegramResponder;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelCreatedEvent;
use HardcorePhp\Infrastructure\MessageBusBundle\Mapping\Handler;
use Telephantast\Message\Message;
use Telephantast\MessageBus\MessageContext;

final readonly class NotifyNewParcelHandler
{
    /**
     * @param array{Admin: list<string>, Manager: list<string>, Picker: list<string>, Volunteer: list<string>} $roles
     */
    public function __construct(
        private NotifyTelegramResponder $responder,
        private array $roles,
    ) {
    }

    /**
     * @template TResult
     * @template TMessage of Message<TResult>
     * @param MessageContext<TResult, TMessage> $messageContext
     */
    #[Handler]
    public function handle(ParcelCreatedEvent $event, MessageContext $messageContext): void
    {
        $users = array_filter($this->roles['Manager'], static fn($id) => $id !== $event->userId);
        $commands = $this->responder->getCreateParcelMessages($users, $event->parcel);
        foreach ($commands as $command) {
            $messageContext->dispatch($command);
        }
    }
}
