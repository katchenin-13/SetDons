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
#[UniqueEntity(['mission'], message: 'Ce code est déjà utilisé')]
#[ORM\Table(name: 'code')]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un code')]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un code')]

    private ?string $ordremission = null;

    #[ORM\Column(length: 255)]
    private ?string $chefmission = null;

    #[ORM\Column(length: 255)]
    private ?string $objectif = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $debut = null;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    #[ORM\JoinColumn(nullable: false)]
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
