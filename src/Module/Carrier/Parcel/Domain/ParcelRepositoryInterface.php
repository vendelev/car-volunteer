<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Parcel\Domain;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

/**
 * @extends ObjectRepository<Parcel>
 */
interface ParcelRepositoryInterface extends ObjectRepository
{
    public function createQueryBuilder(string $alias, string|null $indexBy = null): QueryBuilder;
}
