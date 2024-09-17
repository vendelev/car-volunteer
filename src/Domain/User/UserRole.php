<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\User;

enum UserRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Receiver = 'receiver';
    case Picker = 'picker';
    case Volunteer = 'volunteer';
    case User = 'user';
}
