<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\Application\UseCases;

use CarVolunteer\Domain\User\UserRole;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Parcel\Domain\ParcelStatus;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\EntryPoint\TelegramAction\EditParcelDescriptionAction;
use CarVolunteer\Module\Carrier\Parcel\EditParcel\EntryPoint\TelegramAction\EditParcelTitleAction;
use CarVolunteer\Module\Carrier\Parcel\ViewParcel\Domain\ViewParcelModel;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

final readonly class ViewParcelUseCase
{
    public function __construct(
        private ParcelRepositoryInterface $parcelRepository,
        private ActionRouteAccess $routeAccess,
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
        if ($item->status !== ParcelStatus::Delivered->value) {
            if (
                $item->authorId === $userId
                || $this->routeAccess->can(EditParcelTitleAction::getInfo()->accessRoles, $roles)
            ) {
                $actions[] = ActionRouteMap::ParcelEditTitle;
            }

            if (
                $item->authorId === $userId
                || $this->routeAccess->can(EditParcelDescriptionAction::getInfo()->accessRoles, $roles)
            ) {
                $actions[] = ActionRouteMap::ParcelEditDescription;
            }
        }

        if ($item->packingId === null) {
            //$item->status === ParcelStatus::Approved->value
            $actions[] = ActionRouteMap::PackParcel;
        } else {
            $actions[] = ActionRouteMap::PackingPhoto;
        }

        if ($item->deliveryId === null) {
            $actions[] = ActionRouteMap::DeliveryCreate;
        } else {
            $actions[] = ActionRouteMap::DeliveryView;

            if ($item->status !== ParcelStatus::Delivered->value) {
                $actions[] = ActionRouteMap::DeliveryFinish;
            }
        }

        if ($item->status !== ParcelStatus::Delivered->value) {
            $actions[] = ActionRouteMap::ParcelDelete;
        }

        return new ViewParcelModel(parcel: $item, actions: $actions);
    }

    /**
     * @return list<Parcel>
     */
    public function getListActiveParcels(): array
    {
        $qb = $this->parcelRepository->createQueryBuilder('p');
        $and = $qb->expr()->in('p.status', [
            ParcelStatus::Described->value,
            ParcelStatus::Approved->value,
            ParcelStatus::Packed->value,
            ParcelStatus::Delivery->value,
        ]);

        return $qb
            ->where($and)
            ->orderBy('p.createAt', 'ASC')
            ->getQuery()->getResult();
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
