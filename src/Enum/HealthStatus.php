<?php

declare(strict_types=1);

namespace App\Enum;

enum HealthStatus: string
{
    case HEALTHY = 'healthy';
    case SICK = 'sick';
    case HUNGRY = 'hungry';
}
