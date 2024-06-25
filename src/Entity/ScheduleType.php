<?php

namespace App\Entity;

use App\Repository\ScheduleTypeRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ScheduleTypeRepository::class)]
class ScheduleType
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['label'])]
    private ?string $slug = null;

    public function __toString(): string
    {
        return $this->label;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    #[ORM\Column(type: Types::DATETIME_MUTABLE, insertable: false, updatable: false,  options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected ?DateTime $createdAt;


    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, insertable: false, updatable: false, columnDefinition: 'DATETIME on update CURRENT_TIMESTAMP')]
    protected ?DateTime $updatedAt;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    /**
     * @var Collection<int, CourseEntry>
     */
    #[ORM\OneToMany(targetEntity: CourseEntry::class, mappedBy: 'scheduleType')]
    private Collection $courseEntries;

    public function __construct()
    {
        $this->courseEntries = new ArrayCollection();
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

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
            $courseEntry->setScheduleType($this);
        }

        return $this;
    }

    public function removeCourseEntry(CourseEntry $courseEntry): static
    {
        if ($this->courseEntries->removeElement($courseEntry)) {
            // set the owning side to null (unless already changed)
            if ($courseEntry->getScheduleType() === $this) {
                $courseEntry->setScheduleType(null);
            }
        }

        return $this;
    }

}
