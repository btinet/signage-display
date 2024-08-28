<?php

namespace App\Entity;

use App\Repository\UntisImportRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: UntisImportRepository::class)]
class UntisImport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filename = null;

    public function __toString(): string
    {
        return $this->filename ?? "kein Dateiname vorhanden";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        if (!$this->filename) {
            return null;
        }
        if (strpos($this->filename, '/') !== false) {
            return $this->filename;
        }
        return sprintf('/uploads/files/%s', $this->filename);
    }

    public function setFilename(?string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getInfo(): string
    {
        return <<<DATA
        Klassen/Kurse, die den Schlüsselwörtern der Ausschlussliste
        entsprechen, werden beim Import ignoriert.
        DATA;
    }

}
