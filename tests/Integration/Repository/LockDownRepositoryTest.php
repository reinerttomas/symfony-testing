<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\LockDown;
use App\Factory\LockDownFactory;
use App\Repository\LockDownRepository;
use PHPUnit\Framework\Attributes\TestDox;
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

    #[TestDox('It should not be in lock down without any rows')]
    public function testIsInLockDownWithNoLockDownRows(): void
    {
        self::assertFalse($this->lockDownRepository->isInLockDown());
    }

    #[TestDox('It should be in lock down when the most recent lock down is active')]
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

    #[TestDox('It should not be in lock down when the most recent lock down is ended')]
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

    #[TestDox('It can find the most recent lock down')]
    public function testCanFindMostRecentLockDown(): void
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

    #[TestDox('It cannot find the most recent lock down without any rows')]
    public function testCannotFindMostRecentLockDown(): void
    {
        $lockDownMostRecent = $this->lockDownRepository->findMostRecent();

        self::assertNull($lockDownMostRecent);
    }
}
