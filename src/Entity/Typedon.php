<?php

namespace App\Entity;

use App\Repository\TypedonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypedonRepository::class)]
class Typedon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdup = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedup = null;

    #[ORM\ManyToOne(inversedBy: 'typedons')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToMany(mappedBy: 'typedon', targetEntity: Fieldon::class)]
    private Collection $qte;

    public function __construct()
    {
        $this->qte = new ArrayCollection();
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
}
