<?php

namespace App\Entity;

use App\Repository\LocaliteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: LocaliteRepository::class)]
#[UniqueEntity(['code'], message: 'Ce code est déjà utilisé')]
class Localite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un code')]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: "Le  code doit faire au moins {{ limit }} caractères",
        maxMessage: "Le  code ne doit pas faire plus de {{ limit }} caractères"
    )]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le libéllé')]
    #[Assert\Length(
        min: 4,
        max: 100,
        minMessage: "Le libéllé doit faire au moins {{ limit }} caractères",
        maxMessage: "Le libéllé ne doit pas faire plus de {{ limit }} caractères"
    )]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'localite', targetEntity: Communaute::class,orphanRemoval: true, cascade:['persist'])]
    private Collection $communautes;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $UpdatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $CreatedAt = null;
    
    #[ORM\ManyToOne(inversedBy: 'localites')]
    #[Gedmo\Blameable(on: 'create')]
    private ?Utilisateur $Utilisateur = null;

    public function __construct()
    {
        $this->communautes = new ArrayCollection();
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

    /**
     * @return Collection<int, Communaute>
     */
    public function getCommunautes(): Collection
    {
        return $this->communautes;
    }

    public function addCommunaute(Communaute $communaute): self
    {
        if (!$this->communautes->contains($communaute)) {
            $this->communautes->add($communaute);
            $communaute->setLocalite($this);
        }

        return $this;
    }

    public function removeCommunaute(Communaute $communaute): self
    {
        if ($this->communautes->removeElement($communaute)) {
            // set the owning side to null (unless already changed)
            if ($communaute->getLocalite() === $this) {
                $communaute->setLocalite(null);
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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->Utilisateur;
    }

    public function setUtilisateur(?Utilisateur $Utilisateur): self
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }


}