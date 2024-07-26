<?php

declare(strict_types=1);

namespace CarVolunteer\Module\ParcelOrder\Domain\Entity;

use CarVolunteer\Module\ParcelOrder\Domain\ParcelStatus;
use CarVolunteer\Module\ParcelOrder\Infrastructure\Repository\ParcelRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use HardcorePhp\Infrastructure\Uuid\DoctrineDBAL\UuidType;
use HardcorePhp\Infrastructure\Uuid\Uuid;

#[ORM\Table(name: 'parcel', schema: 'parcel')]
#[ORM\Entity(repositoryClass: ParcelRepository::class)]
readonly class Parcel
{
    public function __construct(
        #[ORM\Id, ORM\Column(type: UuidType::class)]
        public readonly Uuid $id,
        #[ORM\Column(type: Types::STRING, length: 20)]
        public string $authorId,
        #[ORM\Column(type: Types::STRING, length: 255)]
        public string $status,
        #[ORM\Column(type: Types::TEXT)]
        public string $description
    ) {
    }
}