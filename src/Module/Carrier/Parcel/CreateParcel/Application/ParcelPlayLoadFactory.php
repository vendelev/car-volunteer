<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application;

use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelPlayLoad;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class ParcelPlayLoadFactory
{
    public function __construct(
        private DenormalizerInterface $denormalizer,
    ) {
    }

    public function createFromConversation(?array $playLoad): ParcelPlayLoad
    {
        if (!empty($playLoad)) {
            return $this->denormalizer->denormalize($playLoad, ParcelPlayLoad::class);
        }

        return new ParcelPlayLoad(
            id: Uuid::v7(),
            status: ParcelStatus::New
        );
    }
}