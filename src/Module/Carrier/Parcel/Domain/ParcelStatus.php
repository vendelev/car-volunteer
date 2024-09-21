<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\Domain;

enum ParcelStatus: string
{
    case New = 'new';
    case WaitTitle = 'wait-title';
    case WaitDescription = 'wait-description';
    case Described = 'described';
    case Approved = 'approved';
    case Packed = 'packed';
    case Delivery = 'delivery';
    case Delivered = 'delivered';
    case Deleted = 'deleted';
}
