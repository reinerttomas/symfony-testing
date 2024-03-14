<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\LockDown;
use App\Enum\LockDownStatus;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<LockDown>
 */
final class LockDownFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'reason' => self::faker()->text(255),
            'status' => self::faker()->randomElement(LockDownStatus::cases()),
        ];
    }

    protected static function getClass(): string
    {
        return LockDown::class;
    }

    public function active(): self
    {
        return $this->addState(['status' => LockDownStatus::ACTIVE]);
    }
}
