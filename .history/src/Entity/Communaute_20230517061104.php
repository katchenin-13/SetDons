<?php

namespace App\Entity;

use App\Repository\CommunauteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommunauteRepository::class)]
class Communaute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'communautes')]
    private ?Categorie $categorie = null;

    #[ORM\Column]
    private ?int $nbestmember = null;

    #[ORM\ManyToOne(inversedBy: 'communautes')]
    private ?Localite $localite = null;

    #[ORM\ManyToOne(inversedBy: 'communautes')]
    #[Gedmo\Blameable(on: 'create')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Contact::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $contacts;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Audience::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $audiences;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $UpdatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $CreatedAt = null;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Nompf::class, orphanRemoval: true, cascade:['persist'])]
    private Collection $nompfs;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Numeropf::class,orphanRemoval: true, cascade:['persist'])]
    private Collection $numeropfs;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Emailpf::class,orphanRemoval: true, cascade:['persist'])]
    private Collection $emailpfs;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Mission::class, orphanRemoval: true)]
    private Collection $missions;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Promesse::class, orphanRemoval: true)]
    private Collection $promesses;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Don::class, orphanRemoval: true)]
    private Collection $dons;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Demande::class, orphanRemoval: true)]
    private Collection $demandes;

    //  #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: PointFocal::class,orphanRemoval: true, cascade:['persist'])]
    //  private Collection $pointFocals;

    


    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->audiences = new ArrayCollection();
        // $this->pointFocals = new ArrayCollection();
        $this->nompfs = new ArrayCollection();
        $this->numeropfs = new ArrayCollection();
        $this->emailpfs = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->promesses = new ArrayCollection();
        $this->dons = new ArrayCollection();
        $this->demandes = new ArrayCollection();
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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getNbestmember(): ?int
    {
        return $this->nbestmember;
    }

    public function setNbestmember(int $nbestmember): self
    {
        $this->nbestmember = $nbestmember;

        return $this;
    }

    public function getLocalite(): ?Localite
    {
        return $this->localite;
    }

    public function setLocalite(?Localite $localite): self
    {
        $this->localite = $localite;

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
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setCommunaute($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getCommunaute() === $this) {
                $contact->setCommunaute(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Audience>
     */
    public function getAudiences(): Collection
    {
        return $this->audiences;
    }

    public function addAudience(Audience $audience): self
    {
        if (!$this->audiences->contains($audience)) {
            $this->audiences->add($audience);
            $audience->setCommunaute($this);
        }

        return $this;
    }

    public function removeAudience(Audience $audience): self
    {
        if ($this->audiences->removeElement($audience)) {
            // set the owning side to null (unless already changed)
            if ($audience->getCommunaute() === $this) {
                $audience->setCommunaute(null);
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

    // /**
    //  * @return Collection<int, PointFocal>
    //  */
    // public function getPointFocals(): Collection
    // {
    //     return $this->pointFocals;
    // }

    // public function addPointFocal(PointFocal $pointFocal): self
    // {
    //     if (!$this->pointFocals->contains($pointFocal)) {
    //         $this->pointFocals->add($pointFocal);
    //         $pointFocal->setCommunaute($this);
    //     }

    //     return $this;
    // }

    // public function removePointFocal(PointFocal $pointFocal): self
    // {
    //     if ($this->pointFocals->removeElement($pointFocal)) {
    //         // set the owning side to null (unless already changed)
    //         if ($pointFocal->getCommunaute() === $this) {
    //             $pointFocal->setCommunaute(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Nompf>
     */
    public function getNompfs(): Collection
    {
        return $this->nompfs;
    }

    public function addNompf(Nompf $nompf): self
    {
        if (!$this->nompfs->contains($nompf)) {
            $this->nompfs->add($nompf);
            $nompf->setCommunaute($this);
        }

        return $this;
    }

    public function removeNompf(Nompf $nompf): self
    {
        if ($this->nompfs->removeElement($nompf)) {
            // set the owning side to null (unless already changed)
            if ($nompf->getCommunaute() === $this) {
                $nompf->setCommunaute(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Numeropf>
     */
    public function getNumeropfs(): Collection
    {
        return $this->numeropfs;
    }

    public function addNumeropf(Numeropf $numeropf): self
    {
        if (!$this->numeropfs->contains($numeropf)) {
            $this->numeropfs->add($numeropf);
            $numeropf->setCommunaute($this);
        }

        return $this;
    }

    public function removeNumeropf(Numeropf $numeropf): self
    {
        if ($this->numeropfs->removeElement($numeropf)) {
            // set the owning side to null (unless already changed)
            if ($numeropf->getCommunaute() === $this) {
                $numeropf->setCommunaute(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Emailpf>
     */
    public function getEmailpfs(): Collection
    {
        return $this->emailpfs;
    }

    public function addEmailpf(Emailpf $emailpf): self
    {
        if (!$this->emailpfs->contains($emailpf)) {
            $this->emailpfs->add($emailpf);
            $emailpf->setCommunaute($this);
        }

        return $this;
    }

    public function removeEmailpf(Emailpf $emailpf): self
    {
        if ($this->emailpfs->removeElement($emailpf)) {
            // set the owning side to null (unless already changed)
            if ($emailpf->getCommunaute() === $this) {
                $emailpf->setCommunaute(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->setCommunaute($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getCommunaute() === $this) {
                $mission->setCommunaute(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Promesse>
     */
    public function getPromesses(): Collection
    {
        return $this->promesses;
    }

    public function addPromess(Promesse $promess): self
    {
        if (!$this->promesses->contains($promess)) {
            $this->promesses->add($promess);
            $promess->setCommunaute($this);
        }

        return $this;
    }

    public function removePromess(Promesse $promess): self
    {
        if ($this->promesses->removeElement($promess)) {
            // set the owning side to null (unless already changed)
            if ($promess->getCommunaute() === $this) {
                $promess->setCommunaute(null);
            }
        }

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
            $don->setCommunaute($this);
        }

        return $this;
    }

    public function removeDon(Don $don): self
    {
        if ($this->dons->removeElement($don)) {
            // set the owning side to null (unless already changed)
            if ($don->getCommunaute() === $this) {
                $don->setCommunaute(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setCommunaute($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getCommunaute() === $this) {
                $demande->setCommunaute(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        if (is_null($this->localite) ) {
            return 'NULL';
        }
        return $this->localite;
    }
    public function __toString()
    {
        if () {
            return 'NULL';
        }
        return $this->categorie;
    }
    



    
}
