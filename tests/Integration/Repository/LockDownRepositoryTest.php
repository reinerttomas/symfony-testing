<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\LockDown;
use App\Factory\LockDownFactory;
use App\Repository\LockDownRepository;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test]
    public function it_should_not_be_in_lockdown_without_any_rows(): void
    {
        self::assertFalse($this->lockDownRepository->isInLockDown());
    }

    #[Test]
    public function it_should_be_in_lockdown_when_the_most_recent_lockdown_is_active(): void
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

    #[Test]
    public function is_should_not_be_in_lockdown_when_the_most_recent_lockdown_is_ended(): void
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

    #[Test]
    public function it_can_find_the_most_recent_lock_down(): void
    {
        // Arrange
        /** @var LockDown $lockDown */
        $lockDown = LockDownFactory::createOne([
            'createdAt' => new \DateTimeImmutable('-1 day'),
        ])->object();
        LockDownFactory::createMany(5, [
            'createdAt' => new \DateTimeImmutable('-2 day'),
        ]);

        // Act
        $lockDownMostRecent = $this->lockDownRepository->findMostRecent();

        // Assert
        self::assertNotNull($lockDownMostRecent);
        self::assertSame($lockDown->getId(), $lockDownMostRecent->getId());
    }

    #[Test]
    public function it_cannot_find_the_most_recent_lockdown_without_any_rows(): void
    {
        $lockDownMostRecent = $this->lockDownRepository->findMostRecent();

        self::assertNull($lockDownMostRecent);
    }
}
