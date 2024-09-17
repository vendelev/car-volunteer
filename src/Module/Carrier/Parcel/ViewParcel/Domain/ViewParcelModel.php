<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\Domain;

use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;

final readonly class ViewParcelModel
{
    /**
     * @param list<ActionRouteMap> $actions
     */
    public function __construct(
        public Parcel $parcel,
        public array $actions,
    ) {
    }
}
