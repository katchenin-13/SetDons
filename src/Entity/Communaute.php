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

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Beneficiaire::class)]
    private Collection $beneficiaires;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Rapportmission::class)]
    private Collection $rapportmissions;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Contact::class)]
    private Collection $contacts;

    #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: Audience::class)]
    private Collection $audiences;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $UpdatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $CreatedAt = null;

     #[ORM\OneToMany(mappedBy: 'communaute', targetEntity: PointFocal::class,orphanRemoval: true, cascade:['persist'])]
     private Collection $pointFocals;

    


    public function __construct()
    {
        $this->beneficiaires = new ArrayCollection();
        $this->rapportmissions = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->audiences = new ArrayCollection();
        $this->pointFocals = new ArrayCollection();
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
     * @return Collection<int, Beneficiaire>
     */
    public function getBeneficiaires(): Collection
    {
        return $this->beneficiaires;
    }

    public function addBeneficiaire(Beneficiaire $beneficiaire): self
    {
        if (!$this->beneficiaires->contains($beneficiaire)) {
            $this->beneficiaires->add($beneficiaire);
            $beneficiaire->setCommunaute($this);
        }

        return $this;
    }

    public function removeBeneficiaire(Beneficiaire $beneficiaire): self
    {
        if ($this->beneficiaires->removeElement($beneficiaire)) {
            // set the owning side to null (unless already changed)
            if ($beneficiaire->getCommunaute() === $this) {
                $beneficiaire->setCommunaute(null);
            }
        }

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
            $rapportmission->setCommunaute($this);
        }

        return $this;
    }

    public function removeRapportmission(Rapportmission $rapportmission): self
    {
        if ($this->rapportmissions->removeElement($rapportmission)) {
            // set the owning side to null (unless already changed)
            if ($rapportmission->getCommunaute() === $this) {
                $rapportmission->setCommunaute(null);
            }
        }

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

    /**
     * @return Collection<int, PointFocal>
     */
    public function getPointFocals(): Collection
    {
        return $this->pointFocals;
    }

    public function addPointFocal(PointFocal $pointFocal): self
    {
        if (!$this->pointFocals->contains($pointFocal)) {
            $this->pointFocals->add($pointFocal);
            $pointFocal->setCommunaute($this);
        }

        return $this;
    }

    public function removePointFocal(PointFocal $pointFocal): self
    {
        if ($this->pointFocals->removeElement($pointFocal)) {
            // set the owning side to null (unless already changed)
            if ($pointFocal->getCommunaute() === $this) {
                $pointFocal->setCommunaute(null);
            }
        }

        return $this;
    }



    
}
