<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LockDown;
use App\Enum\LockDownStatus;
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

    public function store(LockDown $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function isInLockDown(): bool
    {
        $qb = $this->createQueryBuilder('ld');

        $lockDown = $qb->orderBy('ld.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($lockDown === null) {
            return false;
        }

        assert($lockDown instanceof LockDown);

        return $lockDown->getStatus() !== LockDownStatus::ENDED;
    }
}
