<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Application\UseCases;

use CarVolunteer\Domain\Photo\SaveNewPhotoCommand;
use CarVolunteer\Module\Carrier\Domain\ParcelPackedEvent;
use CarVolunteer\Module\Carrier\Packing\Application\PackStateMachine;
use CarVolunteer\Module\Carrier\Packing\Domain\PackPlayLoad;
use CarVolunteer\Module\Carrier\Packing\Domain\PackStatus;
use CarVolunteer\Module\Carrier\Packing\Domain\UserClickEvent;
use CarVolunteer\Module\Carrier\Packing\Infrastructure\Repository\PackingRepository;
use Telephantast\MessageBus\MessageBus;

final readonly class CreatePackUseCase
{
    public function __construct(
        private PackingRepository $repository,
        private PackStateMachine $stateMachine,
        private MessageBus $messageBus,
    ) {
    }

    public function handle(
        string $userId,
        PackPlayLoad $pack,
        ?UserClickEvent $clickEvent,
        ?string $photoId,
    ): PackPlayLoad {
        $packing = $this->repository->findOneBy(['parcelId' => $pack->parcelId]);

        if ($packing !== null && $packing->status === PackStatus::Packed) {
            $pack->status = PackStatus::Packed;

            return $pack;
        }

        $pack->status = $this->stateMachine->handle($pack->status, $clickEvent, (bool)$photoId);

        if ($photoId !== null) {
            $this->messageBus->dispatch(new SaveNewPhotoCommand($photoId, $pack->id));
        }

        if ($pack->status === PackStatus::PhotoLoaded) {
            $pack->photoId = $photoId;

            return $pack;
        }

        if ($pack->status === PackStatus::Packed) {
            $this->messageBus->dispatch(new ParcelPackedEvent($userId, $pack->parcelId, $pack->id));

            return $pack;
        }

        return $pack;
    }
}
