<?php

declare(strict_types=1);

namespace CarVolunteer\Component\Photo\Domain;

use CarVolunteer\Component\Photo\Infrastructure\Repository\PhotoRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use HardcorePhp\Infrastructure\Uuid\DoctrineDBAL\UuidType;
use HardcorePhp\Infrastructure\Uuid\Uuid;

#[ORM\Table(name: 'photo', schema: 'photo')]
#[ORM\Index(name: 'object_idx', columns: ['object_id'])]
#[ORM\Entity(repositoryClass: PhotoRepository::class)]
readonly class Photo
{
    public function __construct(
        #[ORM\Id, ORM\Column(type: UuidType::class)]
        public Uuid $id,
        #[ORM\Column(type: UuidType::class)]
        public Uuid $objectId,
        #[ORM\Column(type: Types::STRING, length: 255)]
        public string $photoId,
        #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
        public DateTimeImmutable $createdAt = new DateTimeImmutable()
    ) {
    }
}
