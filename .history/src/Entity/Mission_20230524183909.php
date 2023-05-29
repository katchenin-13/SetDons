<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: MissionRepository::class)]
#[UniqueEntity(['code'], message: 'Ce code est déjà utilisé')]
#[Assert\Length(
    min: 3,
    max: 20,
    minMessage: "Le  code doit faire au moins {{ limit }} caractères",
    maxMessage: "Le  code ne doit pas faire plus de {{ limit }} caractères"
)]

class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un code')]
    private ?string $code = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un titre de mission')]
    private ?string $ordremission = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le nom du chef de mission')]
    private ?string $chefmission = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un objectif')]
    private ?string $objectif = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Veuillez renseigner une date de debut')]
    #[Assert\GreaterThanOrEqual(value: "today", message: "la date de debut doit être superirieure ou égale a aujourd'hui")]
    private ?\DateTimeInterface $debut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Veuillez renseigner la date de retour de la mission')]
    #[Assert\Expression("this.getDateretour() >= this.getDa()", message: "la date de debut doit être superirieure ou égale a aujourd'hui")]

    private ?\DateTimeInterface $dateretour = null;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez seclectionner une communauté')]
    private ?Communaute $communaute = null;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Gedmo\Blameable(on: 'create')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $UpdatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $CreatedAt = null;
    

    #[ORM\OneToMany(mappedBy: 'mission', targetEntity: Rapportmission::class, orphanRemoval: true)]
    private Collection $rapportmissions;

    public function __construct()
    {
        $this->rapportmissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getOrdremission(): ?string
    {
        return $this->ordremission;
    }

    public function setOrdremission(string $ordremission): self
    {
        $this->ordremission = $ordremission;

        return $this;
    }

    public function getChefmission(): ?string
    {
        return $this->chefmission;
    }

    public function setChefmission(string $chefmission): self
    {
        $this->chefmission = $chefmission;

        return $this;
    }

    public function getObjectif(): ?string
    {
        return $this->objectif;
    }

    public function setObjectif(string $objectif): self
    {
        $this->objectif = $objectif;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

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

    public function getCommunaute(): ?Communaute
    {
        return $this->communaute;
    }

    public function setCommunaute(?Communaute $communaute): self
    {
        $this->communaute = $communaute;

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

    public function getCreatedAd(): ?\DateTimeInterface
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

    /**
     * @return Collection<int, Rapportmission>
     */
    public function getRapportmissions(): Collection
    {
        return $this->rapportmissions;
    }

    public function addRapportmission(Rapportmission $rapportmission): self
    {
        if (!$this->rapportmissions->contains($rapportmission)) {
            $this->rapportmissions->add($rapportmission);
            $rapportmission->setMission($this);
        }

        return $this;
    }

    public function removeRapportmission(Rapportmission $rapportmission): self
    {
        if ($this->rapportmissions->removeElement($rapportmission)) {
            // set the owning side to null (unless already changed)
            if ($rapportmission->getMission() === $this) {
                $rapportmission->setMission(null);
            }
        }

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
