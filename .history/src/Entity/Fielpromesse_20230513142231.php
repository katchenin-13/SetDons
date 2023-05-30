<?php

namespace App\Entity;

use App\Repository\FielpromesseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FielpromesseRepository::class)]
class Fielpromesse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $qte = null;

    #[ORM\Column(length: 255)]
    private ?string $nature = null;

    #[ORM\Column(length: 255)]
    private ?string $motif = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'fielpromesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?promesse $promesse = null;

    #[ORM\ManyToOne(inversedBy: 'fielpromesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): self
    {
        $this->nature = $nature;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getPromesse(): ?promesse
    {
        return $this->promesse;
    }

    public function setPromesse(?promesse $promesse): self
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

    
}