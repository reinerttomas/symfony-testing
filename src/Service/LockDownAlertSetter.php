<?php

declare(strict_types=1);

namespace App\Service;

interface LockDownAlertSetter
{
    public function clearLockDownAlerts(): void;
}
