<?php

declare(strict_types=1);

use Symfony\Config\FrameworkConfig;

/* @phpstan-ignore-next-line */
return static function (FrameworkConfig $framework): void {
    $messenger = $framework->messenger(); // @phpstan-ignore-line
    $messenger->transport('sync')->dsn('sync://');
};
