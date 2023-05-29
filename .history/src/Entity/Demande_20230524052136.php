<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length:300 , type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le motif ')]
    private ?string $motif = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Veuillez renseigner la date de la rencontre')]
    private ?\DateTimeInterface $daterencontre = null;
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Veuillez renseigner le lieu d'habitation")]
    private ?string $lieu_habitation = null;

    #[ORM\Column(length: 14)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le numéro')]
    private ?string $numero = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: tru)]
    #[Assert\NotBlank(message: 'Veuillez selectionner des la communaute')]
    private ?Communaute $communaute = null;

    #[ORM\Column(nullable: true)]
    private ?bool $statusdemande = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[Gedmo\Blameable(on: 'create')]
    private ?Utilisateur $utilisateur = null;



    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $UpdatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $CreatedAt = null;
 
    public function getDaterencontre(): ?\DateTimeInterface
    {
        return $this->daterencontre;
    }

    public function setDaterencontre(\DateTimeInterface $daterencontre): self
    {
        $this->daterencontre = $daterencontre;

        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
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


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    
    public function getLieuHabitation(): ?string
    {
        return $this->lieu_habitation;
    }

    public function setLieuHabitation(string $lieu_habitation): self
    {
        $this->lieu_habitation = $lieu_habitation;

        return $this;
    }
    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

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
    public function isStatusdemande(): ?bool
    {
        return $this->statusdemande;
    }

    public function setStatusdemande(bool $statusdemande): self
    {
        $this->statusdemande = $statusdemande;

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

    public function __toString()
    {
        if (is_null($this->communaute)) {
            return 'NULL';
        }
        return $this->communaute;
    }
}
