<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Dinosaur;
use App\Enum\HealthStatus;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Dinosaur>
 */
final class DinosaurFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->name,
            'genus' => self::faker()->words(3, true),
            'length' => self::faker()->randomNumber(2),
            'enclosure' => self::faker()->word(),
            'health' => self::faker()->randomElement(HealthStatus::cases()),
        ];
    }

    protected static function getClass(): string
    {
        return Dinosaur::class;
    }
}
