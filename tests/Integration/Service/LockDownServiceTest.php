<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Enum\LockDownStatus;
use App\Factory\LockDownFactory;
use App\Service\LockDownAlertSetter;
use App\Service\LockDownService;
use LogicException;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;

class LockDownServiceTest extends KernelTestCase
{
    use Factories;

    #[TestDox('It should end the current lock down')]
    public function testEndCurrentLockDown(): void
    {
        self::bootKernel();

        $lockDown = LockDownFactory::new()
            ->active()
            ->create();

        $lockDownSetter = $this->createMock(LockDownAlertSetter::class);
        $lockDownSetter->expects(self::once())
            ->method('clearLockDownAlerts');
        self::getContainer()->set(LockDownAlertSetter::class, $lockDownSetter);

        $lockDownService = static::getContainer()->get(LockDownService::class);

        $lockDownService->endCurrentLockDown();
        self::assertSame(LockDownStatus::ENDED, $lockDown->object()->getStatus());
    }

    #[TestDox('It should fail to end the current lock down when the lock down is not active')]
    public function testEndCurrentLockDownWithoutLockDown(): void
    {
        self::bootKernel();

        $lockDownService = static::getContainer()->get(LockDownService::class);

        self::expectException(LogicException::class);
        self::expectExceptionMessage('There is no lock down to end');

        $lockDownService->endCurrentLockDown();
    }
}
