<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application;

use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Domain\DeliveryPlayLoad;
use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class DeliveryPlayLoadFactory
{
    public function __construct(
        private DenormalizerInterface $denormalizer,
    ) {
    }

    public function createFromConversation(string $parcelId, ?array $playLoad): DeliveryPlayLoad
    {
        if (!empty($playLoad)) {
            return $this->denormalizer->denormalize($playLoad, DeliveryPlayLoad::class);
        }

        return new DeliveryPlayLoad(
            id: Uuid::v7(),
            parcelId: Uuid::fromString($parcelId),
            status: DeliveryStatus::New
        );
    }
}