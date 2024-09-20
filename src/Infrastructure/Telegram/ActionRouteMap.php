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
    case ParcelEditDescription = '/editParcel';

    case PackParcel = '/packParcel';

    case DeliveryCreate = '/createDelivery';
    case DeliveryFinish = '/finishDelivery';
    case DeliveryView = '/viewDelivery';
    case PackingPhoto = '/viewPackingPhoto';
}
