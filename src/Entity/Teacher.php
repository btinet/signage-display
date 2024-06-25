<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher
{
    use MetaEntityTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $abbreviation = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['abbreviation'])]
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @var Collection<int, ClassGroup>
     */
    #[ORM\OneToMany(targetEntity: ClassGroup::class, mappedBy: 'teacher')]
    private Collection $classGroups;

    /**
     * @var Collection<int, Course>
     */
    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'teacher')]
    private Collection $courses;

    /**
     * @var Collection<int, SuspensionEntry>
     */
    #[ORM\OneToMany(targetEntity: SuspensionEntry::class, mappedBy: 'teacher')]
    private Collection $suspensionEntries;

    /**
     * @var Collection<int, CourseEntry>
     */
    #[ORM\OneToMany(targetEntity: CourseEntry::class, mappedBy: 'plannedTeacher')]
    private Collection $courseEntries;

    /**
     * @var Collection<int, CourseEntry>
     */
    #[ORM\OneToMany(targetEntity: CourseEntry::class, mappedBy: 'updatedTeacher')]
    private Collection $courseEntriesStandIn;

    public function __construct()
    {
        $this->classGroups = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->suspensionEntries = new ArrayCollection();
        $this->courseEntries = new ArrayCollection();
        $this->courseEntriesStandIn = new ArrayCollection();
    }

    public function __toString(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(string $abbreviation): static
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * @return Collection<int, ClassGroup>
     */
    public function getClassGroups(): Collection
    {
        return $this->classGroups;
    }

    public function addClassGroup(ClassGroup $classGroup): static
    {
        if (!$this->classGroups->contains($classGroup)) {
            $this->classGroups->add($classGroup);
            $classGroup->setTeacher($this);
        }

        return $this;
    }

    public function removeClassGroup(ClassGroup $classGroup): static
    {
        if ($this->classGroups->removeElement($classGroup)) {
            // set the owning side to null (unless already changed)
            if ($classGroup->getTeacher() === $this) {
                $classGroup->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setTeacher($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getTeacher() === $this) {
                $course->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SuspensionEntry>
     */
    public function getSuspensionEntries(): Collection
    {
        return $this->suspensionEntries;
    }

    public function addSuspensionEntry(SuspensionEntry $suspensionEntry): static
    {
        if (!$this->suspensionEntries->contains($suspensionEntry)) {
            $this->suspensionEntries->add($suspensionEntry);
            $suspensionEntry->setTeacher($this);
        }

        return $this;
    }

    public function removeSuspensionEntry(SuspensionEntry $suspensionEntry): static
    {
        if ($this->suspensionEntries->removeElement($suspensionEntry)) {
            // set the owning side to null (unless already changed)
            if ($suspensionEntry->getTeacher() === $this) {
                $suspensionEntry->setTeacher(null);
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
            $courseEntry->setPlannedTeacher($this);
        }

        return $this;
    }

    public function removeCourseEntry(CourseEntry $courseEntry): static
    {
        if ($this->courseEntries->removeElement($courseEntry)) {
            // set the owning side to null (unless already changed)
            if ($courseEntry->getPlannedTeacher() === $this) {
                $courseEntry->setPlannedTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CourseEntry>
     */
    public function getCourseEntriesStandIn(): Collection
    {
        return $this->courseEntriesStandIn;
    }

    public function addCourseEntriesStandIn(CourseEntry $courseEntriesStandIn): static
    {
        if (!$this->courseEntriesStandIn->contains($courseEntriesStandIn)) {
            $this->courseEntriesStandIn->add($courseEntriesStandIn);
            $courseEntriesStandIn->setUpdatedTeacher($this);
        }

        return $this;
    }

    public function removeCourseEntriesStandIn(CourseEntry $courseEntriesStandIn): static
    {
        if ($this->courseEntriesStandIn->removeElement($courseEntriesStandIn)) {
            // set the owning side to null (unless already changed)
            if ($courseEntriesStandIn->getUpdatedTeacher() === $this) {
                $courseEntriesStandIn->setUpdatedTeacher(null);
            }
        }

        return $this;
    }


}
