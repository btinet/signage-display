<?php

namespace App\Entity;

use App\Repository\DisplayMacroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DisplayMacroRepository::class)]
class DisplayMacro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, BlogPost>
     */
    #[ORM\ManyToMany(targetEntity: BlogPost::class, inversedBy: 'displayMacros')]
    private Collection $displayBlogPosts;

    /**
     * @var Collection<int, ShoutOut>
     */
    #[ORM\ManyToMany(targetEntity: ShoutOut::class, inversedBy: 'displayMacros')]
    private Collection $displayTickers;

    #[ORM\Column]
    private ?bool $showScheduleColumn = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $validFrom = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $vaildTo = null;

    #[ORM\Column]
    private ?bool $active = null;

    /**
     * @var Collection<int, RunningDisplayMacro>
     */
    #[ORM\OneToMany(targetEntity: RunningDisplayMacro::class, mappedBy: 'displayMacro')]
    private Collection $runningDisplayMacros;

    /**
     * @var Collection<int, BlogPost>
     */
    #[ORM\ManyToMany(targetEntity: BlogPost::class)]
    #[ORM\JoinTable(name: 'display_macro_prev_blog_post')]
    private Collection $prevBlogPosts;

    /**
     * @var Collection<int, ShoutOut>
     */
    #[ORM\ManyToMany(targetEntity: ShoutOut::class)]
    #[ORM\JoinTable(name: 'display_macro_prev_shout_out')]
    private Collection $prevShoutOuts;

    #[ORM\Column(nullable: true)]
    private ?bool $prevShowScheduleColumn = null;

    public function __construct()
    {
        $this->displayBlogPosts = new ArrayCollection();
        $this->displayTickers = new ArrayCollection();
        $this->runningDisplayMacros = new ArrayCollection();
        $this->prevBlogPosts = new ArrayCollection();
        $this->prevShoutOuts = new ArrayCollection();
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

    /**
     * @return Collection<int, BlogPost>
     */
    public function getDisplayBlogPosts(): Collection
    {
        return $this->displayBlogPosts;
    }

    public function addDisplayBlogPost(BlogPost $displayBlogPost): static
    {
        if (!$this->displayBlogPosts->contains($displayBlogPost)) {
            $this->displayBlogPosts->add($displayBlogPost);
        }

        return $this;
    }

    public function removeDisplayBlogPost(BlogPost $displayBlogPost): static
    {
        $this->displayBlogPosts->removeElement($displayBlogPost);

        return $this;
    }

    /**
     * @return Collection<int, ShoutOut>
     */
    public function getDisplayTickers(): Collection
    {
        return $this->displayTickers;
    }

    public function addDisplayTicker(ShoutOut $displayTicker): static
    {
        if (!$this->displayTickers->contains($displayTicker)) {
            $this->displayTickers->add($displayTicker);
        }

        return $this;
    }

    public function removeDisplayTicker(ShoutOut $displayTicker): static
    {
        $this->displayTickers->removeElement($displayTicker);

        return $this;
    }

    public function isShowScheduleColumn(): ?bool
    {
        return $this->showScheduleColumn;
    }

    public function setShowScheduleColumn(bool $showScheduleColumn): static
    {
        $this->showScheduleColumn = $showScheduleColumn;

        return $this;
    }

    public function getValidFrom(): ?\DateTimeImmutable
    {
        return $this->validFrom;
    }

    public function setValidFrom(\DateTimeImmutable $validFrom): static
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    public function getVaildTo(): ?\DateTimeImmutable
    {
        return $this->vaildTo;
    }

    public function setVaildTo(?\DateTimeImmutable $vaildTo): static
    {
        $this->vaildTo = $vaildTo;

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

    /**
     * @return Collection<int, RunningDisplayMacro>
     */
    public function getRunningDisplayMacros(): Collection
    {
        return $this->runningDisplayMacros;
    }

    public function addRunningDisplayMacro(RunningDisplayMacro $runningDisplayMacro): static
    {
        if (!$this->runningDisplayMacros->contains($runningDisplayMacro)) {
            $this->runningDisplayMacros->add($runningDisplayMacro);
            $runningDisplayMacro->setDisplayMacro($this);
        }

        return $this;
    }

    public function removeRunningDisplayMacro(RunningDisplayMacro $runningDisplayMacro): static
    {
        if ($this->runningDisplayMacros->removeElement($runningDisplayMacro)) {
            // set the owning side to null (unless already changed)
            if ($runningDisplayMacro->getDisplayMacro() === $this) {
                $runningDisplayMacro->setDisplayMacro(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public function getPrevBlogPosts(): Collection
    {
        return $this->prevBlogPosts;
    }

    public function addPrevBlogPost(BlogPost $prevBlogPost): static
    {
        if (!$this->prevBlogPosts->contains($prevBlogPost)) {
            $this->prevBlogPosts->add($prevBlogPost);
        }

        return $this;
    }

    public function removePrevBlogPost(BlogPost $prevBlogPost): static
    {
        $this->prevBlogPosts->removeElement($prevBlogPost);

        return $this;
    }

    /**
     * @return Collection<int, ShoutOut>
     */
    public function getPrevShoutOuts(): Collection
    {
        return $this->prevShoutOuts;
    }

    public function addPrevShoutOut(ShoutOut $prevShoutOut): static
    {
        if (!$this->prevShoutOuts->contains($prevShoutOut)) {
            $this->prevShoutOuts->add($prevShoutOut);
        }

        return $this;
    }

    public function removePrevShoutOut(ShoutOut $prevShoutOut): static
    {
        $this->prevShoutOuts->removeElement($prevShoutOut);

        return $this;
    }

    public function isPrevShowScheduleColumn(): ?bool
    {
        return $this->prevShowScheduleColumn;
    }

    public function setPrevShowScheduleColumn(?bool $prevShowScheduleColumn): static
    {
        $this->prevShowScheduleColumn = $prevShowScheduleColumn;

        return $this;
    }
}
