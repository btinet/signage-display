<?php

namespace App\Entity;

use App\Repository\RunningDisplayMacroRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RunningDisplayMacroRepository::class)]
class RunningDisplayMacro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'runningDisplayMacros')]
    private ?DisplayMacro $displayMacro = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $validTo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayMacro(): ?DisplayMacro
    {
        return $this->displayMacro;
    }

    public function setDisplayMacro(?DisplayMacro $displayMacro): static
    {
        $this->displayMacro = $displayMacro;

        return $this;
    }

    public function getValidTo(): ?\DateTimeImmutable
    {
        return $this->validTo;
    }

    public function setValidTo(?\DateTimeImmutable $validTo): static
    {
        $this->validTo = $validTo;

        return $this;
    }
}
