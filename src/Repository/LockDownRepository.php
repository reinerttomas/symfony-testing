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

        $qb->andWhere('ld.status != :endedStatus')
            ->setParameter('endedStatus', LockDownStatus::ENDED)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult() !== null;
    }
}
