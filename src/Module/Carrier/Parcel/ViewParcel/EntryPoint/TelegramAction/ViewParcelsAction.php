<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases\ViewParcelUseCase;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Infrastructure\Presenter\ViewParcelTelegramPresenter;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewParcelsAction implements ActionInterface
{
    public function __construct(
        private ViewParcelUseCase $listUseCase,
        private ViewParcelTelegramPresenter $presenter,
    ) {
    }

    public static function getRoute(): string
    {
        return '/viewParcels';
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $models = $this->listUseCase->getListActiveParcels();
        $command = $this->presenter->viewParcels($message->userId, $models);
        $messageContext->dispatch($command);

        return $message->conversation;
    }
}
