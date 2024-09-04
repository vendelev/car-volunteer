<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\FinishDelivery\Application;

use CarVolunteer\Module\Carrier\Delivery\FinishDelivery\Domain\FinishDeliveryPlayLoad;
use HardcorePhp\Infrastructure\Uuid\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class FinishDeliveryPlayLoadFactory
{
    public function __construct(
        private DenormalizerInterface $denormalizer,
    ) {
    }

    public function createFromConversation(string $parcelId, ?array $playLoad): FinishDeliveryPlayLoad
    {
        if (!empty($playLoad)) {
            return $this->denormalizer->denormalize($playLoad, FinishDeliveryPlayLoad::class);
        }

        return new FinishDeliveryPlayLoad(Uuid::fromString($parcelId));
    }

}