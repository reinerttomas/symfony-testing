<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\LockDown;
use App\Repository\LockDownRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LockDownRepositoryTest extends KernelTestCase
{
    private LockDownRepository $lockDownRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $this->lockDownRepository = $container->get(LockDownRepository::class);
    }

    public function testIsInLockDownWithNoLockDownRows(): void
    {
        self::assertFalse($this->lockDownRepository->isInLockDown());
    }

    public function testIsInLockDownReturnsTrueIfMostRecentLockDownIsActive(): void
    {
        $lockDown = new LockDown('Dinos have organized their own lunch break');
        $this->lockDownRepository->store($lockDown, true);

        self::assertTrue($this->lockDownRepository->isInLockDown());
    }
}
