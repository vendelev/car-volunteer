<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\Domain;

use CarVolunteer\Module\Carrier\Delivery\Infrastructure\Repository\DeliveryRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use HardcorePhp\Infrastructure\Uuid\DoctrineDBAL\UuidType;
use HardcorePhp\Infrastructure\Uuid\Uuid;

#[ORM\Table(name: 'delivery', schema: 'carrier')]
#[ORM\Entity(repositoryClass: DeliveryRepository::class)]
class Delivery
{
    public function __construct(
        #[ORM\Id, ORM\Column(type: UuidType::class)]
        public Uuid $id,
        #[ORM\Column(type: Types::STRING, length: 20)]
        public string $carrierId,
        #[ORM\Column(type: UuidType::class)]
        public Uuid $parcelId,
        #[ORM\Column(type: Types::STRING, length: 255, enumType: DeliveryStatus::class)]
        public DeliveryStatus $status,
        #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
        public DateTimeImmutable $deliveryAt,
        #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
        public DateTimeImmutable $createAt = new DateTimeImmutable(),
    ) {
    }
}
