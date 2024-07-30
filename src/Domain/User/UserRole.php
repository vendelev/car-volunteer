<?php

declare(strict_types=1);

namespace CarVolunteer\Domain\User;

enum UserRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Recipient = 'recipient';
    case Picker = 'picker';
    case Volunteer = 'volunteer';
}
