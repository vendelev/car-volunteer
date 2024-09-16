<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\EditParcel\Application\UseCases;

use CarVolunteer\Component\AccessRights\Application\RightsChecker;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelPlayLoad;
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
    public function handle(string $userId, ParcelPlayLoad $playLoad, array $roles, ?string $message): ?ParcelPlayLoad
    {
        /** @var Parcel|null $entity */
        $entity = $this->parcelRepository->findOneBy(['id' => $playLoad->id]);

        if ($entity === null) {
            return null;
        }

        if ($playLoad->status === ParcelStatus::New) {
            $playLoad->status = ParcelStatus::from($entity->status);
            $playLoad->title = $entity->title;
            $playLoad->description = $entity->description;
        }

        if (!in_array($playLoad->status, [ParcelStatus::Described, ParcelStatus::WaitDescription], true)) {
            return null;
        }

        if (!$this->rightsChecker->canEdit($entity->authorId, $userId, $roles)) {
            return null;
        }

        if ($playLoad->status === ParcelStatus::Described) {
            $playLoad->status = ParcelStatus::WaitDescription;

            return $playLoad;
        }

        $playLoad->status = ParcelStatus::Described;
        $entity->description = $message ?? '';

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $playLoad;
    }
}
