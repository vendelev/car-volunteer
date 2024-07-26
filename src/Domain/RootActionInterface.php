<?php

declare(strict_types=1);

namespace CarVolunteer\Domain;

interface RootActionInterface extends ActionInterface
{
    public static function getTitle(): string;
}