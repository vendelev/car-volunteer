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

        if ($currentStatus === PackStatus::WaitPhoto) {
            return $hasPhotoId ? PackStatus::PhotoLoaded : PackStatus::New;
        }

        if ($currentStatus === PackStatus::PhotoLoaded) {
            return $clickEvent === UserClickEvent::Packing ? PackStatus::WaitPack : PackStatus::New;
        }

        if ($currentStatus === PackStatus::WaitPack) {
            return $clickEvent === UserClickEvent::Packed ? PackStatus::Packed : PackStatus::New;
        }

        return $currentStatus;
    }
}
