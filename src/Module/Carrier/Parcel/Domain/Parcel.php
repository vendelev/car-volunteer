<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\Domain;

use CarVolunteer\Module\Carrier\Parcel\Infrastructure\Repository\ParcelRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use HardcorePhp\Infrastructure\Uuid\DoctrineDBAL\UuidType;
use HardcorePhp\Infrastructure\Uuid\Uuid;

#[ORM\Table(name: 'parcel', schema: 'carrier')]
#[ORM\Entity(repositoryClass: ParcelRepository::class)]
class Parcel
{
    public function __construct(
        #[ORM\Id, ORM\Column(type: UuidType::class)]
        public Uuid $id,
        #[ORM\Column(type: Types::STRING, length: 20)]
        public string $authorId,
        #[ORM\Column(type: Types::STRING, length: 255)]
        public string $status,
        #[ORM\Column(type: Types::STRING, length: 255)]
        public string $title,
        #[ORM\Column(type: Types::TEXT)]
        public string $description,
        #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
        public DateTimeImmutable $createAt,
        #[ORM\Column(type: UuidType::class, nullable: true)]
        public ?Uuid $packingId = null,
        #[ORM\Column(type: UuidType::class, nullable: true)]
        public ?Uuid $deliveryId = null,
    ) {
    }
}
