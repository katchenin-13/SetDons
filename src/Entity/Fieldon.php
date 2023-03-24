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

    #[ORM\Column]
    private ?float $qte = null;

    #[ORM\Column(length: 255)]
    private ?string $naturedon = null;

    #[ORM\Column(length: 255)]
    private ?string $motifdon = null;

    #[ORM\Column]
    private ?float $montantdon = null;

    #[ORM\ManyToOne(inversedBy: 'fieldons')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToMany(mappedBy: 'fieldon', targetEntity: Don::class)]
    private Collection $dons;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $UpdatedAt = null;

    private $updated;
    public function __construct()
    {
        $this->dons = new ArrayCollection();
    }

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

    public function getQte(): ?float
    {
        return $this->qte;
    }

    public function setQte(float $qte): self
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

    /**
     * @return Collection<int, Don>
     */
    public function getDons(): Collection
    {
        return $this->dons;
    }

    public function addDon(Don $don): self
    {
        if (!$this->dons->contains($don)) {
            $this->dons->add($don);
            $don->setFieldon($this);
        }

        return $this;
    }

    public function removeDon(Don $don): self
    {
        if ($this->dons->removeElement($don)) {
            // set the owning side to null (unless already changed)
            if ($don->getFieldon() === $this) {
                $don->setFieldon(null);
            }
        }

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
