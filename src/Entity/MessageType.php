<?php

namespace App\Entity;

use App\Repository\MessageTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageTypeRepository::class)]
class MessageType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $cssClass = null;

    /**
     * @var Collection<int, ShoutOut>
     */
    #[ORM\OneToMany(targetEntity: ShoutOut::class, mappedBy: 'type')]
    private Collection $shoutOuts;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(nullable: true)]
    private ?bool $useColor = null;

    public function __construct()
    {
        $this->shoutOuts = new ArrayCollection();
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

    public function getCssClass(): ?string
    {
        return $this->cssClass;
    }

    public function setCssClass(string $cssClass): static
    {
        $this->cssClass = $cssClass;

        return $this;
    }

    /**
     * @return Collection<int, ShoutOut>
     */
    public function getShoutOuts(): Collection
    {
        return $this->shoutOuts;
    }

    public function addShoutOut(ShoutOut $shoutOut): static
    {
        if (!$this->shoutOuts->contains($shoutOut)) {
            $this->shoutOuts->add($shoutOut);
            $shoutOut->setType($this);
        }

        return $this;
    }

    public function removeShoutOut(ShoutOut $shoutOut): static
    {
        if ($this->shoutOuts->removeElement($shoutOut)) {
            // set the owning side to null (unless already changed)
            if ($shoutOut->getType() === $this) {
                $shoutOut->setType(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function isUseColor(): ?bool
    {
        return $this->useColor;
    }

    public function setUseColor(?bool $useColor): static
    {
        $this->useColor = $useColor;

        return $this;
    }
}
