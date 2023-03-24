<?php

namespace App\Entity;

use App\Repository\AudienceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AudienceRepository::class)]
class Audience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $motif = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $daterencontre = null;

    #[ORM\Column(length: 255)]
    private ?string $nomchef = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column]
    private ?int $nombreparticipant = null;

    #[ORM\Column(length: 255)]
    private ?string $nomdesparticipant = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $observation = null;

    #[ORM\ManyToOne(inversedBy: 'audiences')]
    private ?Communaute $communaute = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdup = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedup = null;

    #[ORM\ManyToOne(inversedBy: 'audiences')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column]
    private ?bool $statusaudience = null;

    #[ORM\Column]
    private ?bool $mentions = null;

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

    public function getDaterencontre(): ?\DateTimeInterface
    {
        return $this->daterencontre;
    }

    public function setDaterencontre(\DateTimeInterface $daterencontre): self
    {
        $this->daterencontre = $daterencontre;

        return $this;
    }

    public function getNomchef(): ?string
    {
        return $this->nomchef;
    }

    public function setNomchef(string $nomchef): self
    {
        $this->nomchef = $nomchef;

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

    public function getNombreparticipant(): ?int
    {
        return $this->nombreparticipant;
    }

    public function setNombreparticipant(int $nombreparticipant): self
    {
        $this->nombreparticipant = $nombreparticipant;

        return $this;
    }

    public function getNomdesparticipant(): ?string
    {
        return $this->nomdesparticipant;
    }

    public function setNomdesparticipant(string $nomdesparticipant): self
    {
        $this->nomdesparticipant = $nomdesparticipant;

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

    public function getCommunaute(): ?Communaute
    {
        return $this->communaute;
    }

    public function setCommunaute(?Communaute $communaute): self
    {
        $this->communaute = $communaute;

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

    public function isStatusaudience(): ?bool
    {
        return $this->statusaudience;
    }

    public function setStatusaudience(bool $statusaudience): self
    {
        $this->statusaudience = $statusaudience;

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
}
