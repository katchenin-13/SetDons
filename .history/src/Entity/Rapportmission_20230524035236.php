<?php

namespace App\Entity;

use Gedmo\Timestampable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\RapportmissionRepository;
use App\Componen

#[ORM\Entity(repositoryClass: RapportmissionRepository::class)]
class Rapportmission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateretour = null;
    #[ORM\Column(type: Types::TEXT)]
    private ?string $action = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $opportunite = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $difficulte = null;

   #[ORM\ManyToOne(inversedBy: 'rapportmissions')]
    #[Gedmo\Blameable(on: 'create')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $UpdatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $CreatedAt = null;
    

    #[ORM\Column(length: 255)]
    private ?string $prochaineetape = null;

    #[ORM\ManyToOne(inversedBy: 'rapportmissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mission $mission = null;


    

   


    public function getId(): ?int
    {
        return $this->id;
    }

       

    public function getDifficulte(): ?string
    {
        return $this->difficulte;
    }

    public function setDifficulte(string $difficulte): self
    {
        $this->difficulte = $difficulte;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getOpportunite(): ?string
    {
        return $this->opportunite;
    }

    public function setOpportunite(string $opportunite): self
    {
        $this->opportunite = $opportunite;

        return $this;
    }

    public function getProchaineetape(): ?string
    {
        return $this->prochaineetape;
    }

    public function setProchaineetape(string $prochaineetape): self
    {
        $this->prochaineetape = $prochaineetape;

        return $this;
    }

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

   
    public function getDateretour(): ?\DateTimeInterface
    {
        return $this->dateretour;
    }

    public function setDateretour(\DateTimeInterface $dateretour): self
    {
        $this->dateretour = $dateretour;

        return $this;
    }

    
}