<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LockDown;
use App\Enum\LockDownStatus;
use App\Repository\Query\LockDownSelectMostRecent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LockDown>
 */
class LockDownRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LockDown::class);
    }

    public function isInLockDown(): bool
    {
        $qb = $this->createQueryBuilder('ld');

        /** @var LockDown|null $lockDown */
        $lockDown = LockDownSelectMostRecent::create($qb)
            ->build()
            ->getQuery()
            ->getOneOrNullResult();

        if ($lockDown === null) {
            return false;
        }

        return $lockDown->getStatus() !== LockDownStatus::ENDED;
    }

    public function findMostRecent(): ?LockDown
    {
        $qb = $this->createQueryBuilder('ld');

        /** @var LockDown|null $lockDown */
        $lockDown = LockDownSelectMostRecent::create($qb)
            ->build()
            ->getQuery()
            ->getOneOrNullResult();

        return $lockDown;
    }
}
