<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $chefmission = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $objectif = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $actionmission = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $opportinuite = null;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    #[Gedmo\Blameable(on: 'create')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToMany(mappedBy: 'mission', targetEntity: Rapportmission::class)]
    private Collection $rapportmissions;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $UpdatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $CreatedAt = null;
    

    private $updated;

    public function __construct()
    {
        $this->rapportmissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function getActionmission(): ?string
    {
        return $this->actionmission;
    }

    public function setActionmission(string $actionmission): self
    {
        $this->actionmission = $actionmission;

        return $this;
    }

    public function getOpportinuite(): ?string
    {
        return $this->opportinuite;
    }

    public function setOpportinuite(string $opportinuite): self
    {
        $this->opportinuite = $opportinuite;

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

    
}
