<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Domain;

use CarVolunteer\Module\Carrier\Packing\Infrastructure\Repository\PackingRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use HardcorePhp\Infrastructure\Uuid\DoctrineDBAL\UuidType;
use HardcorePhp\Infrastructure\Uuid\Uuid;

#[ORM\Table(name: 'packing', schema: 'carrier')]
#[ORM\Entity(repositoryClass: PackingRepository::class)]
readonly class Packing
{
    public function __construct(
        #[ORM\Id, ORM\Column(type: UuidType::class)]
        public Uuid $id,
        #[ORM\Column(type: Types::STRING, length: 20)]
        public string $pickerId,
        #[ORM\Column(type: UuidType::class)]
        public Uuid $parcelId,
        #[ORM\Column(type: Types::STRING, length: 255)]
        public string $status,
        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        public ?string $photoId = null,
        #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
        public DateTimeImmutable $createAt = new DateTimeImmutable(),
    ) {
    }
}
