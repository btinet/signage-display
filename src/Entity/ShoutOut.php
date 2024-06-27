<?php

namespace App\Entity;

use App\Repository\ShoutOutRepository;
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
}
