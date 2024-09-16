<?php

declare(strict_types=1);

namespace CarVolunteer\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class KernelTestCaseDecorator extends KernelTestCase
{
    /**
     * Получить сервис по его названию
     *
     * @template T
     *
     * @param class-string<T> $className
     * @return T
     */
    public static function getService(string $className): mixed
    {
        return self::getContainer()->get($className); // @phpstan-ignore-line
    }
}
