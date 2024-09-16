<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases;

use CarVolunteer\Component\AccessRights\Application\RightsChecker;
use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Domain\ViewParcelModel;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Domain\ViewParcelActions;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

final readonly class ViewParcelUseCase
{
    public function __construct(
        private ParcelRepositoryInterface $parcelRepository,
        private RightsChecker $rightsChecker,
    ) {
    }

    /**
     * @param list<UserRole> $roles
     */
    public function getViewParcel(string $userId, string $parcelId, array $roles): ?ViewParcelModel
    {
        $item = $this->parcelRepository->findOneBy(['id' => $parcelId]);

        if ($item === null) {
            return null;
        }

        $actions = [];
        if ($item->packingId === null) {
            if ($this->rightsChecker->canEdit($item->authorId, $userId, $roles)) {
                $actions[] = ViewParcelActions::EditParcel;
            }

            if (
                $item->status === ParcelStatus::Approved->value
                && in_array(UserRole::Picker, $roles, true)
            ) {
                $actions[] = ViewParcelActions::PackParcel;
            }
        }

        if ($item->deliveryId === null && in_array(UserRole::Manager, $roles, true)) {
            $actions[] = ViewParcelActions::CreateDelivery;
        }

        if ($item->deliveryId !== null && $item->status !== ParcelStatus::Delivered->value) {
            $actions[] = ViewParcelActions::FinishDelivery;
        }

        return new ViewParcelModel(parcel: $item, actions: $actions);
    }

    /**
     * @return list<Parcel>
     */
    public function getListActiveParcels(): array
    {
        return $this->parcelRepository->findBy(['status' => [
            ParcelStatus::Described->value,
            ParcelStatus::Approved->value,
            ParcelStatus::Packed->value,
            ParcelStatus::Delivery->value,
            ParcelStatus::Shipped->value,
        ]]);
    }

    /**
     * @return list<Parcel>
     */
    public function getListArchiveParcels(): array
    {
        $qb = $this->parcelRepository->createQueryBuilder('p');

        $and = $qb->expr()->andX();
        $and->add($qb->expr()->eq('p.status', ':status'));
        $and->add($qb->expr()->gte('p.createAt', ':minDate'));

        return $qb->where($and)
            ->setParameters(new ArrayCollection([
                new Parameter('status', ParcelStatus::Delivered->value),
                new Parameter('minDate', (new DateTimeImmutable())->modify('-14 days')->format('Y-m-d')),
            ]))
            ->orderBy('p.createAt', 'ASC')
            ->getQuery()->getResult();
    }
}
