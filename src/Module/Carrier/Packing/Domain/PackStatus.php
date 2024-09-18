<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Domain;

enum PackStatus: string
{
    case New = 'new';
    case WaitPhoto = 'wait-photo';
    case PhotoLoaded = 'photo-loaded';
    case WaitPack = 'wait-pack';
    case Packed = 'packed';
}
