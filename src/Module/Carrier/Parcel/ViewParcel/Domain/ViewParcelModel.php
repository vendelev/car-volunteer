<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\Domain;

use CarVolunteer\Module\Carrier\Parcel\Domain\Parcel;

final readonly class ViewParcelModel
{
    /**
     * @param list<ViewParcelActions> $actions
     */
    public function __construct(
        public Parcel $parcel,
        public array $actions,
    ) {
    }
}
