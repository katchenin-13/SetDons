<?php

namespace App\Entity;

use App\Repository\PromesseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PromesseRepository::class)]
class Promesse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
        #[Assert\NotBlank(message: 'Veuillez renseigner le nom du point focal')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le nom du point focal')]
    private ?string $numero = null;

    #[ORM\Column(length: 60, nullable:true)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'promesses')]
    #[ORM\JoinColumn(nullable: false)]
        #[Assert\NotBlank(message: 'Veuillez selectionner une communauté')]

    private ?Communaute $communaute = null;

   
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateremise = null;



    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $UpdatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $CreatedAt = null;
    


    #[ORM\ManyToOne(inversedBy: 'promesses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Gedmo\Blameable(on: 'create')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $statusdon = null;

    #[ORM\OneToMany(mappedBy: 'promesse', targetEntity: Fielpromesse::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $fielpromesses;

    public function __construct()
    {
        $this->fielpromesses = new ArrayCollection();
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

 

    public function isStatusdon(): ?bool
    {
        return $this->statusdon;
    }

    public function setStatusdon(bool $statusdon): self
    {
        $this->statusdon = $statusdon;

        return $this;
    }

    /**
     * @return Collection<int, Fielpromesse>
     */
    public function getFielpromesses(): Collection
    {
        return $this->fielpromesses;
    }

    public function addFielpromess(Fielpromesse $fielpromess): self
    {
        if (!$this->fielpromesses->contains($fielpromess)) {
            $this->fielpromesses->add($fielpromess);
            $fielpromess->setPromesse($this);
        }

        return $this;
    }

    public function removeFielpromess(Fielpromesse $fielpromess): self
    {
        if ($this->fielpromesses->removeElement($fielpromess)) {
            // set the owning side to null (unless already changed)
            if ($fielpromess->getPromesse() === $this) {
                $fielpromess->setPromesse(null);
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
