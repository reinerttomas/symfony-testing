<?php

declare(strict_types=1);

namespace App\Enum;

enum Size: string
{
    case LARGE = 'large';
    case MEDIUM = 'medium';
    case SMALL = 'small';

    public function description(): string
    {
        return match ($this) {
            self::LARGE => 'Large',
            self::MEDIUM => 'Medium',
            self::SMALL => 'Small',
        };
    }
}
