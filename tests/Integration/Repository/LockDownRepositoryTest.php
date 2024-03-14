<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Enum\LockDownStatus;
use App\Factory\LockDownFactory;
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
        LockDownFactory::createOne([
            'createdAt' => new \DateTimeImmutable('-1 day'),
            'status' => LockDownStatus::ACTIVE,
        ]);
        LockDownFactory::createMany(5, [
            'createdAt' => new \DateTimeImmutable('-2 day'),
            'status' => LockDownStatus::ENDED,
        ]);

        self::assertTrue($this->lockDownRepository->isInLockDown());
    }

    public function testIsInLockDownReturnsFalseIfMostRecentLockDownIsNotActive(): void
    {
        LockDownFactory::createOne([
            'createdAt' => new \DateTimeImmutable('-1 day'),
            'status' => LockDownStatus::ENDED,
        ]);
        LockDownFactory::createMany(5, [
            'createdAt' => new \DateTimeImmutable('-2 day'),
            'status' => LockDownStatus::ACTIVE,
        ]);

        self::assertFalse($this->lockDownRepository->isInLockDown());
    }
}
