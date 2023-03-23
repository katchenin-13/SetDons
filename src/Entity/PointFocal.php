<?php

namespace App\Entity;

use App\Repository\PointFocalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PointFocalRepository::class)]
class PointFocal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $numeropf = null;

    #[ORM\Column(length: 255)]
    private ?string $emailpf = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdup = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedup = null;

    #[ORM\ManyToOne(inversedBy: 'pointFocals')]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNumeropf(): ?string
    {
        return $this->numeropf;
    }

    public function setNumeropf(string $numeropf): self
    {
        $this->numeropf = $numeropf;

        return $this;
    }

    public function getEmailpf(): ?string
    {
        return $this->emailpf;
    }

    public function setEmailpf(string $emailpf): self
    {
        $this->emailpf = $emailpf;

        return $this;
    }

    public function getCreatedup(): ?\DateTimeInterface
    {
        return $this->createdup;
    }

    public function setCreatedup(\DateTimeInterface $createdup): self
    {
        $this->createdup = $createdup;

        return $this;
    }

    public function getUpdatedup(): ?\DateTimeInterface
    {
        return $this->updatedup;
    }

    public function setUpdatedup(\DateTimeInterface $updatedup): self
    {
        $this->updatedup = $updatedup;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
