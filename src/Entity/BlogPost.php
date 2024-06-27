<?php

namespace App\Entity;

use App\Repository\BlogPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: BlogPostRepository::class)]
class BlogPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'blogPosts')]
    private ?BlogPostTemplate $template = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $featuredImage = null;

    #[ORM\Column]
    private ?bool $titleVisible = null;

    #[ORM\Column]
    private ?bool $contentVisible = null;

    #[ORM\Column]
    private ?bool $featuredImageVisible = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'blogPosts')]
    private ?ImageGallery $gallery = null;

    /**
     * @var Collection<int, ListEntry>
     */
    #[ORM\ManyToMany(targetEntity: ListEntry::class, inversedBy: 'blogPosts', cascade: ["persist"])]
    private Collection $list;

    public function __construct()
    {
        $this->list = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getTemplate(): ?BlogPostTemplate
    {
        return $this->template;
    }

    public function setTemplate(?BlogPostTemplate $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getFeaturedImage(): ?string
    {
        return $this->featuredImage;
    }

    public function getFeaturedImageUrl(): ?string
    {
        if (!$this->featuredImage) {
            return null;
        }
        if (strpos($this->featuredImage, '/') !== false) {
            return $this->featuredImage;
        }
        return sprintf('/posts/uploads/%s', $this->featuredImage);
    }

    public function setFeaturedImage(?string $featuredImage): static
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    public function isTitleVisible(): ?bool
    {
        return $this->titleVisible;
    }

    public function setTitleVisible(bool $titleVisible): static
    {
        $this->titleVisible = $titleVisible;

        return $this;
    }

    public function isContentVisible(): ?bool
    {
        return $this->contentVisible;
    }

    public function setContentVisible(bool $contentVisible): static
    {
        $this->contentVisible = $contentVisible;

        return $this;
    }

    public function isFeaturedImageVisible(): ?bool
    {
        return $this->featuredImageVisible;
    }

    public function setFeaturedImageVisible(bool $featuredImageVisible): static
    {
        $this->featuredImageVisible = $featuredImageVisible;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getGallery(): ?ImageGallery
    {
        return $this->gallery;
    }

    public function setGallery(?ImageGallery $gallery): static
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * @return Collection<int, ListEntry>
     */
    public function getList(): Collection
    {
        return $this->list;
    }

    public function addList(ListEntry $list): static
    {
        if (!$this->list->contains($list)) {
            $this->list->add($list);
        }

        return $this;
    }

    public function removeList(ListEntry $list): static
    {
        $this->list->removeElement($list);

        return $this;
    }
}
