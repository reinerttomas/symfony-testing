<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\LockDown;
use App\Factory\LockDownFactory;
use App\Repository\LockDownRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;

class LockDownRepositoryTest extends KernelTestCase
{
    use Factories;
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
        LockDownFactory::new()
            ->active()
            ->create([
                'createdAt' => new \DateTimeImmutable('-1 day'),
            ]);
        LockDownFactory::new()
            ->ended()
            ->many(5)
            ->create([
                'createdAt' => new \DateTimeImmutable('-2 day'),
            ]);

        self::assertTrue($this->lockDownRepository->isInLockDown());
    }

    public function testIsInLockDownReturnsFalseIfMostRecentLockDownIsNotActive(): void
    {
        LockDownFactory::new()
            ->active()
            ->many(5)
            ->create([
                'createdAt' => new \DateTimeImmutable('-2 day'),
            ]);
        LockDownFactory::new()
            ->ended()
            ->create([
                'createdAt' => new \DateTimeImmutable('-1 day'),
            ]);

        self::assertFalse($this->lockDownRepository->isInLockDown());
    }

    public function testFindMostRecentLockDown(): void
    {
        /** @var LockDown $lockDown */
        $lockDown = LockDownFactory::createOne([
            'createdAt' => new \DateTimeImmutable('-1 day'),
        ])->object();
        LockDownFactory::createMany(5, [
            'createdAt' => new \DateTimeImmutable('-2 day'),
        ]);

        $lockDownMostRecent = $this->lockDownRepository->findMostRecent();

        self::assertNotNull($lockDownMostRecent);
        self::assertSame($lockDown->getId(), $lockDownMostRecent->getId());
    }
}
