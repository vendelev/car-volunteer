<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Application;

use CarVolunteer\Module\Carrier\Delivery\CreateDelivery\Domain\CreateDeliveryPlayLoad;
use CarVolunteer\Module\Carrier\Delivery\Domain\DeliveryStatus;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class CreateDeliveryPlayLoadFactory
{
    public function __construct(
        private DenormalizerInterface $denormalizer,
    ) {
    }

    public function createFromConversation(string $parcelId, ?array $playLoad): CreateDeliveryPlayLoad
    {
        if (!empty($playLoad)) {
            return $this->denormalizer->denormalize($playLoad, CreateDeliveryPlayLoad::class);
        }

        return new CreateDeliveryPlayLoad(
            id: Uuid::v7(),
            parcelId: Uuid::fromString($parcelId),
            status: DeliveryStatus::New
        );
    }
}