<?php

namespace App\Entity;

use App\Repository\DonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonRepository::class)]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?Beneficiaire $beneficiaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateremise = null;

    #[ORM\Column(length: 255)]
    private ?string $remispar = null;

    #[ORM\Column]
    private ?bool $promesse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdup = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedup = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?Fieldon $fieldon = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBeneficiaire(): ?Beneficiaire
    {
        return $this->beneficiaire;
    }

    public function setBeneficiaire(?Beneficiaire $beneficiaire): self
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }

    public function getDateremise(): ?\DateTimeInterface
    {
        return $this->dateremise;
    }

    public function setDateremise(\DateTimeInterface $dateremise): self
    {
        $this->dateremise = $dateremise;

        return $this;
    }

    public function getRemispar(): ?string
    {
        return $this->remispar;
    }

    public function setRemispar(string $remispar): self
    {
        $this->remispar = $remispar;

        return $this;
    }

    public function isPromesse(): ?bool
    {
        return $this->promesse;
    }

    public function setPromesse(bool $promesse): self
    {
        $this->promesse = $promesse;

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

    public function getFieldon(): ?Fieldon
    {
        return $this->fieldon;
    }

    public function setFieldon(?Fieldon $fieldon): self
    {
        $this->fieldon = $fieldon;

        return $this;
    }

 
}
