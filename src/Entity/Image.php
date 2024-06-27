<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file = null;

    #[ORM\Column(nullable: true)]
    private ?bool $disabled = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['file'])]
    private ?string $slug = null;

    /**
     * @var Collection<int, ImageGallery>
     */
    #[ORM\ManyToMany(targetEntity: ImageGallery::class, inversedBy: 'images')]
    private Collection $gallery;

    #[ORM\Column(nullable: true)]
    private ?bool $showCaption = null;

    public function __construct()
    {
        $this->gallery = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title ?? $this->slug;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function getFileUrl(): ?string
    {
        if (!$this->file) {
            return null;
        }
        if (strpos($this->file, '/') !== false) {
            return $this->file;
        }
        return sprintf('/images/uploads/%s', $this->file);
    }

    public function setFile(?string $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function isDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(?bool $disabled): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return Collection<int, ImageGallery>
     */
    public function getGallery(): Collection
    {
        return $this->gallery;
    }

    public function addGallery(ImageGallery $gallery): static
    {
        if (!$this->gallery->contains($gallery)) {
            $this->gallery->add($gallery);
        }

        return $this;
    }

    public function removeGallery(ImageGallery $gallery): static
    {
        $this->gallery->removeElement($gallery);

        return $this;
    }

    public function isShowCaption(): ?bool
    {
        return $this->showCaption;
    }

    public function setShowCaption(?bool $showCaption): static
    {
        $this->showCaption = $showCaption;

        return $this;
    }
}
