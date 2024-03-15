<?php

declare(strict_types=1);

namespace App\Repository\Query;

use Doctrine\ORM\QueryBuilder;

interface Query
{
    public function __construct(QueryBuilder $qb);

    public function build(): QueryBuilder;
}
