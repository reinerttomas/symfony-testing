<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Dinosaur;
use App\Enum\HealthStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{
    #[Test]
    public function it_can_create_dinosaur(): void
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
        self::assertSame(HealthStatus::HEALTHY, $dino->getHealth());
    }

    #[Test]
    #[DataProvider('sizeDescriptionProvider')]
    public function it_should_has_correct_size_description_from_length(int $length, string $expectedSize): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty',
            genus: 'Tyrannosaurus',
            length: $length,
            enclosure: 'Paddock A',
        );

        self::assertSame($expectedSize, $dino->getSize()->description());
    }

    public static function sizeDescriptionProvider(): iterable
    {
        yield [
            'length' => 10,
            'expectedSize' => 'Large',
        ];

        yield [
            'length' => 5,
            'expectedSize' => 'Medium',
        ];

        yield [
            'length' => 4,
            'expectedSize' => 'Small',
        ];
    }

    #[TestDox('It should accept visitors by default')]
    #[Test]
    public function it_should_accept_visitors_by_default(): void
    {
        $dino = new Dinosaur(
            name: 'Big Eaty',
            genus: 'Tyrannosaurus',
            length: 15,
            enclosure: 'Paddock A',
        );

        self::assertTrue($dino->isAcceptingVisitors());
    }

    #[Test]
    #[DataProvider('healthStatusProvider')]
    public function is_should_accept_visitors_when_not_sick(HealthStatus $healthStatus, bool $expectedVisitorStatus): void
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

    public static function healthStatusProvider(): iterable
    {
        yield [
            HealthStatus::HEALTHY, true,
        ];

        yield [
            HealthStatus::SICK, false,
        ];

        yield [
            HealthStatus::HUNGRY, true,
        ];
    }
}
