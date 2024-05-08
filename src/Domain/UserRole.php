<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

enum UserRole: string
{
    case Manager = 'manager';
    case Picker = 'picker';
    case Volunteer = 'volunteer';
}
