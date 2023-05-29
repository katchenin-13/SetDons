<?php

namespace App\Entity;

use App\Repository\DonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonRepository::class)]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateremise = null;

    #[ORM\Column(length: 255)]
    private ?string $remispar = null;

    

    #[ORM\ManyToOne(inversedBy: 'dons')]
    #[Gedmo\Blameable(on: 'create')]
    private ?Utilisateur $utilisateur = null;



    // #[ORM\Column(nullable:true)]
    // private ?bool $statusdon = null;

  

   v

    

     #[ORM\OneToMany(mappedBy: 'don', targetEntity: Fieldon::class,orphanRemoval: true, cascade:['persist'])]
     private Collection $fieldon;

     #[ORM\Column(length: 255)]
     private ?string $nom = null;

     #[ORM\Column(length: 255)]
     private ?string $numero = null;

     #[ORM\Column(length: 255)]
     private ?string $email = null;

     #[ORM\ManyToOne(inversedBy: 'dons')]
     #[ORM\JoinColumn(nullable: false)]
     private ?Communaute $communaute = null;

     

     public function __construct()
     {
         $this->fieldon = new ArrayCollection();
     }
    


    

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRemispar(): ?string
    {
        return $this->remispar;
    }

    public function setRemispar(string $remispar): self
    {
        $this->remispar = $remispar;

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

    

    // public function isStatusdon(): ?bool
    // {
    //     return $this->statusdon;
    // }

    // public function setStatusdon(bool $statusdon): self
    // {
    //     $this->statusdon = $statusdon;

    //     return $this;
    // }

   

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
     * @return Collection<int, Fieldon>
     */
    public function getFieldon(): Collection
    {
        return $this->fieldon;
    }

    public function addFieldon(Fieldon $fieldon): self
    {
        if (!$this->fieldon->contains($fieldon)) {
            $this->fieldon->add($fieldon);
            $fieldon->setDon($this);
        }

        return $this;
    }

    public function removeFieldon(Fieldon $fieldon): self
    {
        if ($this->fieldon->removeElement($fieldon)) {
            // set the owning side to null (unless already changed)
            if ($fieldon->getDon() === $this) {
                $fieldon->setDon(null);
            }
        }

        return $this;
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

    public function getCommunaute(): ?Communaute
    {
        return $this->communaute;
    }

    public function setCommunaute(?Communaute $communaute): self
    {
        $this->communaute = $communaute;

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
