<?php

declare(strict_types=1);

namespace App\Repository\Query;

use Doctrine\ORM\QueryBuilder;

class LockDownSelectMostRecent extends AbstractQuery
{
    public function build(): QueryBuilder
    {
        return $this->qb
            ->orderBy('ld.createdAt', 'DESC')
            ->setMaxResults(1);
    }
}
