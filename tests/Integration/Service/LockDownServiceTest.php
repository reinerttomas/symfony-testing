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
use Zenstruck\Mailer\Test\InteractsWithMailer;

class LockDownServiceTest extends KernelTestCase
{
    use Factories;
    use InteractsWithMailer;

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

        $this->getLockDownService()->endCurrentLockDown();
        self::assertSame(LockDownStatus::ENDED, $lockDown->object()->getStatus());
    }

    #[TestDox('It should fail to end the current lock down when the lock down is not active')]
    public function testEndCurrentLockDownWithoutLockDown(): void
    {
        self::bootKernel();

        self::expectException(LogicException::class);
        self::expectExceptionMessage('There is no lock down to end');

        $this->getLockDownService()->endCurrentLockDown();
    }

    #[TestDox('It should start lock down when dinosaur escaped')]
    public function testDinosaurEscapedPersistsLockDown(): void
    {
        self::bootKernel();

        $this->getLockDownService()->dinosaurEscaped();
        LockDownFactory::repository()->assert()->count(1);

        self::mailer()->assertSentEmailCount(1);
        self::mailer()->assertEmailSentTo('staff@dinotopia.com', 'PARK LOCKDOWN');
    }

    private function getLockDownService(): LockDownService
    {
        return self::getContainer()->get(LockDownService::class);
    }
}
