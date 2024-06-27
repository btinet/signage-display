<?php

namespace App\Entity;

use App\Repository\BlogPostTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: BlogPostTemplateRepository::class)]
class BlogPostTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $templateFile = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['label'])]
    private ?string $slug = null;

    /**
     * @var Collection<int, BlogPost>
     */
    #[ORM\OneToMany(targetEntity: BlogPost::class, mappedBy: 'template')]
    private Collection $blogPosts;

    public function __construct()
    {
        $this->blogPosts = new ArrayCollection();
    }

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

    public function getTemplateFile(): ?string
    {
        return $this->templateFile;
    }

    public function setTemplateFile(string $templateFile): static
    {
        $this->templateFile = $templateFile;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public function getBlogPosts(): Collection
    {
        return $this->blogPosts;
    }

    public function addBlogPost(BlogPost $blogPost): static
    {
        if (!$this->blogPosts->contains($blogPost)) {
            $this->blogPosts->add($blogPost);
            $blogPost->setTemplate($this);
        }

        return $this;
    }

    public function removeBlogPost(BlogPost $blogPost): static
    {
        if ($this->blogPosts->removeElement($blogPost)) {
            // set the owning side to null (unless already changed)
            if ($blogPost->getTemplate() === $this) {
                $blogPost->setTemplate(null);
            }
        }

        return $this;
    }
}
