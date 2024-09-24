<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\EditParcel\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelChangeDescriptionEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelChangeTitleEvent;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Domain\EditParcelPlayLoad;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\Infrastructure\Responder\EditParcelTelegramResponder;

final readonly class EditParcelUseCase
{
    public function __construct(
        private ParcelRepositoryInterface $repository,
        private ActionRouteAccess $routeAccess,
        private EditParcelTelegramResponder $responder,
    ) {
    }

    /**
     * @param list<UserRole> $roles
     * @return list<ParcelChangeTitleEvent|SendMessageCommand>
     */
    public function editTitle(string $userId, EditParcelPlayLoad $playLoad, array $roles): array
    {
        $parcel = $this->repository->findOneBy(['id' => $playLoad->id]);

        if (
            $parcel
            && $parcel->status !== ParcelStatus::Delivered->value
            && $this->routeAccess->get(ActionRouteMap::ParcelEditTitle, $roles)
        ) {
            if (!$playLoad->text) {
                $commands = $this->responder->getEditTitleButton($userId, $parcel->title, $roles);
            } else {
                $parcel->title = $playLoad->text;
                $commands = array_merge(
                    [new ParcelChangeTitleEvent($parcel)],
                    $this->responder->getAfterSave($userId, $roles)
                );
            }
        } else {
            $commands = [$this->responder->getEditCancelButton($userId, 'Редактирование не возможно', $roles)];
        }

        return $commands;
    }

    /**
     * @param list<UserRole> $roles
     * @return list<ParcelChangeDescriptionEvent|SendMessageCommand>
     */
    public function editDescription(string $userId, EditParcelPlayLoad $playLoad, array $roles): array
    {
        $parcel = $this->repository->findOneBy(['id' => $playLoad->id]);

        if (
            $parcel
            && $parcel->status !== ParcelStatus::Delivered->value
            && $this->routeAccess->get(ActionRouteMap::ParcelEditDescription, $roles)
        ) {
            if (!$playLoad->text) {
                $commands = $this->responder->getEditDescriptionButton($userId, $parcel->description, $roles);
            } else {
                $parcel->description = $playLoad->text;
                $commands = array_merge(
                    [new ParcelChangeDescriptionEvent($parcel)],
                    $this->responder->getAfterSave($userId, $roles)
                );
            }
        } else {
            $commands = [$this->responder->getEditCancelButton($userId, 'Редактирование не возможно', $roles)];
        }

        return $commands;
    }
}
