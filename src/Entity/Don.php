<?php

namespace App\Entity;

use App\Repository\DonRepository;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
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

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?Fieldon $fieldon = null;

    #[ORM\Column]
    private ?bool $statusdon = null;

    #[ORM\Column]
    private ?bool $mentions = null;

     #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $UpdatedAt = null;

     #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $CreatedAt = null;
    


    

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

    public function isStatusdon(): ?bool
    {
        return $this->statusdon;
    }

    public function setStatusdon(bool $statusdon): self
    {
        $this->statusdon = $statusdon;

        return $this;
    }

    public function isMentions(): ?bool
    {
        return $this->mentions;
    }

    public function setMentions(bool $mentions): self
    {
        $this->mentions = $mentions;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }


    

 
}
