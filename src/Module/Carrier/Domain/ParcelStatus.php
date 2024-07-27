<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Domain;

enum ParcelStatus: string
{
    case New = 'new';
    case WaitTitle = 'wait-title';
    case WaitDescription = 'wait-description';
    case Described = 'described';
}