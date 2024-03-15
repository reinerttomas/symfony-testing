<?php

declare(strict_types=1);

namespace App\Service;

use App\Doctrine\EntityManager;
use App\Enum\LockDownStatus;
use App\Repository\LockDownRepository;

readonly class LockDownService
{
    public function __construct(
        private LockDownRepository $lockDownRepository,
        private EntityManager $em,
    ) {
    }

    public function endCurrentLockDown(): void
    {
        $lockDown = $this->lockDownRepository->findMostRecent();

        if ($lockDown === null) {
            throw new \LogicException('There is no lock down to end');
        }

        $lockDown->setStatus(LockDownStatus::ENDED);
        $this->em->flush();
    }
}
