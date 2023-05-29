<?php

namespace App\Entity;

use App\Repository\TypedonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TypedonRepository::class)]
#[UniqueEntity(['typedon'], message: 'Ce code est déjà utilisé')]
#[ORM\Table(name: 'code')]
class Typedon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20 , unique:true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un code')]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un libe')]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'typedons')]
    #[Gedmo\Blameable(on: 'create')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToMany(mappedBy: 'typedon', targetEntity: Fieldon::class,orphanRemoval: true, cascade:['persist'])]
    private Collection $qte;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $UpdatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $CreatedAt = null;

    #[ORM\OneToMany(mappedBy: 'typedon', targetEntity: Fielpromesse::class, orphanRemoval: true)]
    private Collection $fielpromesses;


    public function __construct()
    {
        $this->qte = new ArrayCollection();
        $this->fielpromesses = new ArrayCollection();
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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
     * @return Collection<int, Fieldon>
     */
    public function getQte(): Collection
    {
        return $this->qte;
    }

    public function addQte(Fieldon $qte): self
    {
        if (!$this->qte->contains($qte)) {
            $this->qte->add($qte);
            $qte->setTypedon($this);
        }

        return $this;
    }

    public function removeQte(Fieldon $qte): self
    {
        if ($this->qte->removeElement($qte)) {
            // set the owning side to null (unless already changed)
            if ($qte->getTypedon() === $this) {
                $qte->setTypedon(null);
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
            $fielpromess->setTypedon($this);
        }

        return $this;
    }

    public function removeFielpromess(Fielpromesse $fielpromess): self
    {
        if ($this->fielpromesses->removeElement($fielpromess)) {
            // set the owning side to null (unless already changed)
            if ($fielpromess->getTypedon() === $this) {
                $fielpromess->setTypedon(null);
            }
        }

        return $this;
    }

    
}
