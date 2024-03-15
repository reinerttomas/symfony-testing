<?php

declare(strict_types=1);

namespace App\Service\GitHub;

use App\Service\LockDownAlertSetter;
use Psr\Log\LoggerInterface;

readonly class GitHubLockDownAlertSetter implements LockDownAlertSetter
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function clearLockDownAlerts(): void
    {
        $this->logger->info('Clearing lock down alerts on GitHub...');
        // pretend like this make an API call to GitHub
    }
}
