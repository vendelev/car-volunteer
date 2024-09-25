<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Application;

use CarVolunteer\Module\Carrier\Packing\Domain\PackStatus;
use CarVolunteer\Module\Carrier\Packing\Domain\UserClickEvent;

final readonly class PackStateMachine
{
    public function handle(PackStatus $currentStatus, ?UserClickEvent $clickEvent, bool $hasPhotoId): PackStatus
    {
        if ($currentStatus === PackStatus::New) {
            if ($clickEvent === UserClickEvent::LoadPhoto) {
                return PackStatus::WaitPhoto;
            }

            if ($clickEvent === UserClickEvent::Packing) {
                return PackStatus::WaitPack;
            }

            return PackStatus::New;
        }

        if ($currentStatus === PackStatus::WaitPhoto && $hasPhotoId) {
            return PackStatus::PhotoLoaded;
        }

        if ($currentStatus === PackStatus::PhotoLoaded && $clickEvent === UserClickEvent::Packing) {
            return PackStatus::WaitPack;
        }

        if ($currentStatus === PackStatus::WaitPack && $clickEvent === UserClickEvent::Packed) {
            return PackStatus::Packed;
        }

        return $currentStatus;
    }
}
