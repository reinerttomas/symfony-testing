<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Dinosaur;
use App\Enum\HealthStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{
    public function testCanGetAndSetData(): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty',
            genus: 'Tyrannosaurus',
            length: 15,
            enclosure: 'Paddock A',
        );

        self::assertSame('Big Eaty', $dino->getName());
        self::assertSame('Tyrannosaurus', $dino->getGenus());
        self::assertSame(15, $dino->getLength());
        self::assertSame('Paddock A', $dino->getEnclosure());
    }

    #[DataProvider('provideSizeDescription')]
    public function testDinoHasCorrectSizeDescriptionFromLength(int $length, string $expectedSize): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty',
            genus: 'Tyrannosaurus',
            length: $length,
            enclosure: 'Paddock A',
        );

        self::assertSame($expectedSize, $dino->getSize()->description());
    }

    public static function provideSizeDescription(): iterable
    {
        yield [
            10, 'Large',
        ];

        yield [
            5, 'Medium',
        ];

        yield [
            4, 'Small',
        ];
    }

    public function testIsAcceptingVisitorsByDefault(): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty',
            genus: 'Tyrannosaurus',
            length: 15,
            enclosure: 'Paddock A',
        );

        self::assertTrue($dino->isAcceptingVisitors());
    }

    #[DataProvider('provideHealthStatus')]
    public function testIsAcceptingVisitorsBasedOnHealthStatus(HealthStatus $healthStatus, bool $expectedVisitorStatus): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty',
            genus: 'Tyrannosaurus',
            length: 15,
            enclosure: 'Paddock A',
        );

        $dino->setHealth($healthStatus);

        self::assertSame($expectedVisitorStatus, $dino->isAcceptingVisitors());
    }

    public static function provideHealthStatus(): iterable
    {
        yield [
            HealthStatus::SICK, false,
        ];

        yield [
            HealthStatus::HUNGRY, true,
        ];
    }
}
