<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\LockDownStatus;
use App\Repository\LockDownRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LockDownRepository::class)]
class LockDown
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: 'string', length: 32, enumType: LockDownStatus::class)]
    private LockDownStatus $status;

    #[ORM\Column(length: 255)]
    private string $reason;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $endedAt = null;

    public function __construct(string $reason)
    {
        $this->status = LockDownStatus::ACTIVE;
        $this->reason = $reason;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): LockDownStatus
    {
        return $this->status;
    }

    public function setStatus(LockDownStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEndedAt(): ?DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(DateTimeImmutable $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }
}
