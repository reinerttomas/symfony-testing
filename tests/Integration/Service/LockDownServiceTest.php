<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Enum\LockDownStatus;
use App\Factory\LockDownFactory;
use App\Service\LockDownAlertSetter;
use App\Service\LockDownService;
use LogicException;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Mailer\Test\InteractsWithMailer;

class LockDownServiceTest extends KernelTestCase
{
    use Factories;
    use InteractsWithMailer;

    #[Test]
    public function it_should_end_the_current_lockdown(): void
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

    #[Test]
    public function it_should_fail_to_end_the_current_lockdown_when_the_lockdown_is_not_active(): void
    {
        self::bootKernel();

        self::expectException(LogicException::class);
        self::expectExceptionMessage('There is no lock down to end');

        $this->getLockDownService()->endCurrentLockDown();
    }

    #[Test]
    public function it_should_start_lockdown_when_dinosaur_escaped(): void
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
