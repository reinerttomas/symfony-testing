<?php

declare(strict_types=1);

namespace App\Service\HealthReport;

use App\Enum\HealthStatus;

interface HealthReportGetter
{
    public function getHealthReport(string $dinosaurName): HealthStatus;
}
