<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\CreateParcel\Application;

use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelPlayLoad;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class ParcelPlayLoadFactory
{
    public function __construct(
        private NormalizerInterface $normalizer,
        private DenormalizerInterface $denormalizer,
    ) {
    }

    /**
     * @param array{id: string, status?: string, title?: string, description?: string}|null $playLoad
     */
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

    /**
     * @return array{id: string, status?: string, title?: string, description?: string}
     */
    public function toArray(ParcelPlayLoad $playLoad): array
    {
        /** @var array{id: non-falsy-string, status?: string, title?: string, description?: string} */
        return $this->normalizer->normalize($playLoad);
    }
}
