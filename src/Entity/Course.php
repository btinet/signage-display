<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[UniqueEntity('internLabel')]
class Course
{
    use MetaEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $internLabel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?SchoolSubject $subject = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?Teacher $teacher = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['internLabel'])]
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @var Collection<int, ClassGroup>
     */
    #[ORM\ManyToMany(targetEntity: ClassGroup::class, inversedBy: 'courses')]
    private Collection $classGroup;

    #[ORM\Column(nullable: true)]
    private ?bool $extended = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    /**
     * @var Collection<int, CourseEvent>
     */
    #[ORM\OneToMany(targetEntity: CourseEvent::class, mappedBy: 'course')]
    private Collection $courseEvents;

    /**
     * @var Collection<int, CourseEntry>
     */
    #[ORM\OneToMany(targetEntity: CourseEntry::class, mappedBy: 'course')]
    private Collection $courseEntries;

    public function __construct()
    {
        $this->classGroup = new ArrayCollection();
        $this->courseEvents = new ArrayCollection();
        $this->courseEntries = new ArrayCollection();
    }

    public function __toString(): string
    {
        return "{$this->internLabel}-{$this->startDate?->format('Y-m')}";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getInternLabel(): ?string
    {
        return $this->internLabel;
    }

    public function setInternLabel(string $internLabel): static
    {
        $this->internLabel = $internLabel;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSubject(): ?SchoolSubject
    {
        return $this->subject;
    }

    public function setSubject(?SchoolSubject $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return Collection<int, ClassGroup>
     */
    public function getClassGroup(): Collection
    {
        return $this->classGroup;
    }

    public function addClassGroup(ClassGroup $classGroup): static
    {
        if (!$this->classGroup->contains($classGroup)) {
            $this->classGroup->add($classGroup);
        }

        return $this;
    }

    public function removeClassGroup(ClassGroup $classGroup): static
    {
        $this->classGroup->removeElement($classGroup);

        return $this;
    }

    public function isExtended(): ?bool
    {
        return $this->extended;
    }

    public function setExtended(?bool $extended): static
    {
        $this->extended = $extended;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection<int, CourseEvent>
     */
    public function getCourseEvents(): Collection
    {
        return $this->courseEvents;
    }

    public function addCourseEvent(CourseEvent $courseEvent): static
    {
        if (!$this->courseEvents->contains($courseEvent)) {
            $this->courseEvents->add($courseEvent);
            $courseEvent->setCourse($this);
        }

        return $this;
    }

    public function removeCourseEvent(CourseEvent $courseEvent): static
    {
        if ($this->courseEvents->removeElement($courseEvent)) {
            // set the owning side to null (unless already changed)
            if ($courseEvent->getCourse() === $this) {
                $courseEvent->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CourseEntry>
     */
    public function getCourseEntries(): Collection
    {
        return $this->courseEntries;
    }

    public function addCourseEntry(CourseEntry $courseEntry): static
    {
        if (!$this->courseEntries->contains($courseEntry)) {
            $this->courseEntries->add($courseEntry);
            $courseEntry->setCourse($this);
        }

        return $this;
    }

    public function removeCourseEntry(CourseEntry $courseEntry): static
    {
        if ($this->courseEntries->removeElement($courseEntry)) {
            // set the owning side to null (unless already changed)
            if ($courseEntry->getCourse() === $this) {
                $courseEntry->setCourse(null);
            }
        }

        return $this;
    }
}
