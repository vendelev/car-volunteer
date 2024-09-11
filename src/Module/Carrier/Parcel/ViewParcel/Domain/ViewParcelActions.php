<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\ViewParcel\Domain;

/**
 * Список действий при просмотре заказ-нарядов
 */
enum ViewParcelActions
{
    case PackParcel;
    case CreateDelivery;
    case FinishDelivery;
}
