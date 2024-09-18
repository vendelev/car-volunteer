<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Application;

use CarVolunteer\Module\Carrier\Packing\Domain\PackPlayLoad;
use CarVolunteer\Module\Carrier\Packing\Domain\PackStatus;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class PackPlayLoadFactory
{
    public function __construct(
        private DenormalizerInterface $denormalizer,
    ) {
    }

    /**
     * @param array<mixed>|null $playLoad
     */
    public function createFromConversation(string $parcelId, ?array $playLoad): PackPlayLoad
    {
        if (!empty($playLoad)) {
            return $this->denormalizer->denormalize($playLoad, PackPlayLoad::class);
        }

        return new PackPlayLoad(
            id: Uuid::v7(),
            parcelId: Uuid::fromString($parcelId),
            status: PackStatus::New
        );
    }
}
