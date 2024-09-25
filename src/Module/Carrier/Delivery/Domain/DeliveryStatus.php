<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Delivery\Domain;

enum DeliveryStatus: string
{
    case New = 'new';
    case WaitDate = 'wait-date';
    case WaitCarrier = 'wait-carrier';
    case WaitConfirm = 'wait-confirm';
    case WaitDelivery = 'wait-delivery';
    case Delivered = 'delivered';
    case Deleted = 'deleted';
}
