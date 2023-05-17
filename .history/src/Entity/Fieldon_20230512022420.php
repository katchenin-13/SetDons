<?php

namespace App\Entity;

use App\Repository\FieldonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FieldonRepository::class)]
class Fieldon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'qte')]
    private ?Typedon $typedon = null;

    #[ORM\Column(nullable:true)]
    private ?int $qte = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $naturedon = null;

    #[ORM\Column(length: 255)]
    private ?string $motifdon = null;

    #[ORM\Column]
    private ?float $montantdon = null;

    #[ORM\ManyToOne(inversedBy: 'fieldons')]
    #[Gedmo\Blameable(on: 'create')]
    private ?Utilisateur $utilisateur = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $UpdatedAt = null;

     #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $CreatedAt = null;

     #[ORM\ManyToOne(inversedBy: 'fieidon')]
     private ?Don $don = null;

     #[ORM\Column(length: 255)]
     private ?string $promessse = null;

     #[ORM\ManyToOne(inversedBy: 'fieldons')]
     #[ORM\JoinColumn(nullable: false)]
     private ?Promesse $promesse = null;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypedon(): ?Typedon
    {
        return $this->typedon;
    }

    public function setTypedon(?Typedon $typedon): self
    {
        $this->typedon = $typedon;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getNaturedon(): ?string
    {
        return $this->naturedon;
    }

    public function setNaturedon(string $naturedon): self
    {
        $this->naturedon = $naturedon;

        return $this;
    }

    public function getMotifdon(): ?string
    {
        return $this->motifdon;
    }

    public function setMotifdon(string $motifdon): self
    {
        $this->motifdon = $motifdon;

        return $this;
    }

    public function getMontantdon(): ?float
    {
        return $this->montantdon;
    }

    public function setMontantdon(float $montantdon): self
    {
        $this->montantdon = $montantdon;

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

 
     public function getUpdatedAt(): ?\DateTimeInterface
        {
            return $this->UpdatedAt;
        }

    public function setUpdatedAt(\DateTimeInterface $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

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

    public function getDon(): ?Don
    {
        return $this->don;
    }

    public function setDon(?Don $don): self
    {
        $this->don = $don;

        return $this;
    }

    public function getPromessse(): ?string
    {
        return $this->promessse;
    }

    public function setPromessse(string $promessse): self
    {
        $this->promessse = $promessse;

        return $this;
    }

    public function getPromesse(): ?Promesse
    {
        return $this->promesse;
    }

    public function setPromesse(?Promesse $promesse): self
    {
        $this->promesse = $promesse;

        return $this;
    }

   
}
