<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Enum\LockDownStatus;
use App\Factory\LockDownFactory;
use App\Service\LockDownService;
use LogicException;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;

class LockDownServiceTest extends KernelTestCase
{
    use Factories;
    private LockDownService $lockDownService;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $this->lockDownService = $container->get(LockDownService::class);
    }

    #[TestDox('It should end the current lock down')]
    public function testEndCurrentLockDown(): void
    {
        $lockDown = LockDownFactory::new()
            ->active()
            ->create();

        $this->lockDownService->endCurrentLockDown();
        self::assertSame(LockDownStatus::ENDED, $lockDown->object()->getStatus());
    }

    #[TestDox('It should fail to end the current lock down when the lock down is not active')]
    public function testEndCurrentLockDownWithoutLockDown(): void
    {
        self::expectException(LogicException::class);
        self::expectExceptionMessage('There is no lock down to end');

        $this->lockDownService->endCurrentLockDown();
    }
}
