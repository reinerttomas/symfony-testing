<?php

declare(strict_types=1);

namespace App\Service;

use App\Doctrine\EntityManager;
use App\Entity\LockDown;
use App\Enum\LockDownStatus;
use App\Repository\LockDownRepository;
use App\Service\Action\SendEmailAlert;

readonly class LockDownService
{
    public function __construct(
        private LockDownRepository $lockDownRepository,
        private EntityManager $em,
        private LockDownAlertSetter $lockDownAlertSetter,
        private SendEmailAlert $sendEmailAlert,
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

        $this->lockDownAlertSetter->clearLockDownAlerts();
    }

    public function dinosaurEscaped(): void
    {
        $lockDown = new LockDown('Dino escaped... NOT good...');

        $this->em->persist($lockDown);
        $this->em->flush();

        // send an email with subject like RUUUN!!!!!
        $this->sendEmailAlert->execute();
    }
}
