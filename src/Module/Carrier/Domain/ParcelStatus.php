<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Domain;

enum ParcelStatus: string
{
    case New = 'new';
    case WaitDescription = 'wait-description';
    case Described = 'described';
}