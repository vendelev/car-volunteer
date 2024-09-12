<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\EditParcel\Application\UseCases;

use CarVolunteer\Component\AccessRights\Application\RightsChecker;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use Doctrine\ORM\EntityManagerInterface;

final readonly class EditParcelUseCase
{
    public function __construct(
        private ParcelRepositoryInterface $parcelRepository,
        private RightsChecker $rightsChecker,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param list<UserRole> $roles
     */
    public function handle(string $userId, string $parcelId, array $roles, ?string $message): ?Parcel
    {
        /** @var Parcel|null $entity */
        $entity = $this->parcelRepository->findOneBy(['id' => $parcelId]);

        if ($entity === null) {
            return null;
        }

        if (!in_array($entity->status, [ParcelStatus::Described->value, ParcelStatus::WaitDescription->value], true)) {
            return null;
        }

        if (!$this->rightsChecker->canEdit($entity->authorId, $userId, $roles)) {
            return null;
        }

        if ($entity->status === ParcelStatus::Described->value) {
            $entity->status = ParcelStatus::WaitDescription->value;
            $result = $entity;
        } else {
            $entity->status = ParcelStatus::Described->value;
            $entity->description = $message ?? '';
            $result = null;
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $result;
    }
}
