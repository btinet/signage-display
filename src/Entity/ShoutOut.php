<?php

namespace App\Entity;

use App\Repository\ShoutOutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoutOutRepository::class)]
class ShoutOut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column]
    private ?bool $disabled = null;

    #[ORM\ManyToOne(inversedBy: 'shoutOuts')]
    private ?MessageType $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cssClass = null;

    /**
     * @var Collection<int, DisplayMacro>
     */
    #[ORM\ManyToMany(targetEntity: DisplayMacro::class, mappedBy: 'displayTickers')]
    private Collection $displayMacros;

    public function __construct()
    {
        $this->displayMacros = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->message;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function isDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getType(): ?MessageType
    {
        return $this->type;
    }

    public function setType(?MessageType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCssClass(): ?string
    {
        return $this->cssClass;
    }

    public function setCssClass(?string $cssClass): static
    {
        $this->cssClass = $cssClass;

        return $this;
    }

    /**
     * @return Collection<int, DisplayMacro>
     */
    public function getDisplayMacros(): Collection
    {
        return $this->displayMacros;
    }

    public function addDisplayMacro(DisplayMacro $displayMacro): static
    {
        if (!$this->displayMacros->contains($displayMacro)) {
            $this->displayMacros->add($displayMacro);
            $displayMacro->addDisplayTicker($this);
        }

        return $this;
    }

    public function removeDisplayMacro(DisplayMacro $displayMacro): static
    {
        if ($this->displayMacros->removeElement($displayMacro)) {
            $displayMacro->removeDisplayTicker($this);
        }

        return $this;
    }
}
