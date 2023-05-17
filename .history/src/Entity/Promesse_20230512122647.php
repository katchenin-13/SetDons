<?php

namespace App\Entity;

use App\Repository\PromesseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromesseRepository::class)]
class Promesse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

  
    
    #[ORM\ManyToOne(inversedBy: 'promesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'promesses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Communaute $communaute = null;

    #[ORM\OneToMany(mappedBy: 'promesse', targetEntity: Fieldon::class, orphanRemoval: true)]
    private Collection $fieldons;

    #[ORM\Column]
    private ?bool $statusdon = null;

    public function __construct()
    {
        $this->fieldons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateremise(): ?\DateTimeInterface
    {
        return $this->dateremise;
    }

    public function setDateremise(\DateTimeInterface $dateremise): self
    {
        $this->dateremise = $dateremise;

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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

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

    /**
     * @return Collection<int, Fieldon>
     */
    public function getFieldons(): Collection
    {
        return $this->fieldons;
    }

    public function addFieldon(Fieldon $fieldon): self
    {
        if (!$this->fieldons->contains($fieldon)) {
            $this->fieldons->add($fieldon);
            $fieldon->setPromesse($this);
        }

        return $this;
    }

    public function removeFieldon(Fieldon $fieldon): self
    {
        if ($this->fieldons->removeElement($fieldon)) {
            // set the owning side to null (unless already changed)
            if ($fieldon->getPromesse() === $this) {
                $fieldon->setPromesse(null);
            }
        }

        return $this;
    }

    public function isStatusdon(): ?bool
    {
        return $this->statusdon;
    }

    public function setStatusdon(bool $statusdon): self
    {
        $this->statusdon = $statusdon;

        return $this;
    }
}
