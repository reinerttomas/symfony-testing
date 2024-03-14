<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\HealthStatus;
use App\Enum\Size;
use App\Repository\DinosaurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DinosaurRepository::class)]
class Dinosaur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 128)]
    private string $genus;

    #[ORM\Column()]
    private int $length;

    #[ORM\Column(length: 128)]
    private string $enclosure;

    #[ORM\Column(type: 'string', length: 32, enumType: HealthStatus::class)]
    private HealthStatus $health;

    public function __construct(string $name, string $genus, int $length, string $enclosure)
    {
        $this->name = $name;
        $this->genus = $genus;
        $this->length = $length;
        $this->enclosure = $enclosure;
        $this->health = HealthStatus::HEALTHY;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGenus(): string
    {
        return $this->genus;
    }

    public function setGenus(string $genus): self
    {
        $this->genus = $genus;

        return $this;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getSize(): Size
    {
        if ($this->length >= 10) {
            return Size::LARGE;
        }

        if ($this->length >= 5) {
            return Size::MEDIUM;
        }

        return Size::SMALL;
    }

    public function getEnclosure(): string
    {
        return $this->enclosure;
    }

    public function setEnclosure(string $enclosure): self
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    public function getHealth(): HealthStatus
    {
        return $this->health;
    }

    public function setHealth(HealthStatus $health): self
    {
        $this->health = $health;

        return $this;
    }

    public function isAcceptingVisitors(): bool
    {
        return $this->health !== HealthStatus::SICK;
    }
}
