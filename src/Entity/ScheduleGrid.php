<?php

namespace App\Entity;

use App\Repository\ScheduleGridRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleGridRepository::class)]
class ScheduleGrid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $hour = null;

    #[ORM\Column(length: 4)]
    private ?string $startTime = null;

    #[ORM\Column(length: 4)]
    private ?string $endTime = null;

    public function __toString(): string
    {
        return 'Stunde ' . $this->hour ?? 'Zeitraster';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHour(): ?string
    {
        return $this->hour;
    }

    public function setHour(?string $hour): static
    {
        $this->hour = $hour;

        return $this;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function setStartTime(string $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    public function setEndTime(string $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }
}
