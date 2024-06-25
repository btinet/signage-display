<?php

namespace App\Entity;

use App\Repository\CourseEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseEventRepository::class)]
class CourseEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: Weekday::class)]
    private ?Weekday $weekday = null;

    #[ORM\Column(enumType: Block::class)]
    private ?Block $class = null;

    #[ORM\ManyToOne(inversedBy: 'courseEvents')]
    private ?Course $course = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $plannedRoom = null;

    public function __toString(): string
    {
        return $this->course;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeekday(): ?Weekday
    {
        return $this->weekday;
    }

    public function setWeekday(Weekday $weekday): static
    {
        $this->weekday = $weekday;

        return $this;
    }

    public function getClass(): ?Block
    {
        return $this->class;
    }

    public function setClass(Block $class): static
    {
        $this->class = $class;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getPlannedRoom(): ?string
    {
        return $this->plannedRoom;
    }

    public function setPlannedRoom(?string $plannedRoom): static
    {
        $this->plannedRoom = $plannedRoom;

        return $this;
    }
}
