<?php

namespace App\Entity;

use App\Repository\CourseEntryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseEntryRepository::class)]
class CourseEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'courseEntries')]
    private ?Course $course = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $entryDate = null;

    #[ORM\Column(enumType: Block::class)]
    private ?Block $entryTime = null;

    #[ORM\ManyToOne(inversedBy: 'courseEntries')]
    private ?ScheduleType $scheduleType = null;

    #[ORM\Column(length: 255)]
    private ?string $plannedRoom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $updatedRoom = null;

    #[ORM\ManyToOne(inversedBy: 'courseEntries')]
    private ?Teacher $plannedTeacher = null;

    #[ORM\ManyToOne(inversedBy: 'courseEntriesStandIn')]
    private ?Teacher $updatedTeacher = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $message = null;

    public function __toString(): string
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEntryDate(): ?\DateTimeInterface
    {
        return $this->entryDate;
    }

    public function setEntryDate(\DateTimeInterface $entryDate): static
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    public function getEntryTime(): ?Block
    {
        return $this->entryTime;
    }

    public function setEntryTime(Block $entryTime): static
    {
        $this->entryTime = $entryTime;

        return $this;
    }

    public function getScheduleType(): ?ScheduleType
    {
        return $this->scheduleType;
    }

    public function setScheduleType(?ScheduleType $scheduleType): static
    {
        $this->scheduleType = $scheduleType;

        return $this;
    }

    public function getPlannedRoom(): ?string
    {
        return $this->plannedRoom;
    }

    public function setPlannedRoom(string $plannedRoom): static
    {
        $this->plannedRoom = $plannedRoom;

        return $this;
    }

    public function getUpdatedRoom(): ?string
    {
        return $this->updatedRoom;
    }

    public function setUpdatedRoom(?string $updatedRoom): static
    {
        $this->updatedRoom = $updatedRoom;

        return $this;
    }

    public function getPlannedTeacher(): ?Teacher
    {
        return $this->plannedTeacher;
    }

    public function setPlannedTeacher(?Teacher $plannedTeacher): static
    {
        $this->plannedTeacher = $plannedTeacher;

        return $this;
    }

    public function getUpdatedTeacher(): ?Teacher
    {
        return $this->updatedTeacher;
    }

    public function setUpdatedTeacher(?Teacher $updatedTeacher): static
    {
        $this->updatedTeacher = $updatedTeacher;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
