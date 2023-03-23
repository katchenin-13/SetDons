<?php

namespace App\Entity;

use App\Repository\RapportmissionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportmissionRepository::class)]
class Rapportmission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rapportmissions')]
    private ?Mission $mission = null;

    #[ORM\ManyToOne(inversedBy: 'rapportmissions')]
    private ?Communaute $communaute = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $difficulte = null;

    #[ORM\Column]
    private ?bool $etape = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $observation = null;

    #[ORM\ManyToOne(inversedBy: 'rapportmissions')]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCommunaute(): ?Communaute
    {
        return $this->communaute;
    }

    public function setCommunaute(?Communaute $communaute): self
    {
        $this->communaute = $communaute;

        return $this;
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

    public function isEtape(): ?bool
    {
        return $this->etape;
    }

    public function setEtape(bool $etape): self
    {
        $this->etape = $etape;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(string $observation): self
    {
        $this->observation = $observation;

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
