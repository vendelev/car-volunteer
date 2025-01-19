<?php

declare(strict_types=1);

namespace CarVolunteer\Infrastructure\Telegram;

enum ActionRouteMap: string
{
    case RootHelp = '/help';
    case RootStart = '/start';

    case ParcelCreate = '/createParcel';
    case ParcelList = '/viewParcels';
    case ParcelDelivered = '/archiveParcels';
    case ParcelView = '/viewParcel';
    case ParcelEditTitle = '/editParcelTitle';
    case ParcelEditDescription = '/editParcelDesc';
    case ParcelDelete = '/deleteParcel';

    case PackParcel = '/packParcel';
    case CancelPacking = '/cancelPacking';

    case DeliveryCreate = '/createDelivery';
    case DeliveryDelete = '/deleteDelivery';
    case DeliveryFinish = '/finishDelivery';
    case DeliveryView = '/viewDelivery';
    case PackingPhoto = '/viewPackingPhoto';
}
