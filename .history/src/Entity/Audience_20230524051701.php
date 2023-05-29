<?php

namespace App\Entity;

use App\Repository\AudienceRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use function PHPSTORM_META\type;

#[ORM\Entity(repositoryClass: AudienceRepository::class)]
class Audience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length:300,type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le motif ')]
    private ?string $motif = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Veuillez renseigner la date de la rencontre')]
    private ?\DateTimeInterface $daterencontre = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le nom de chef de délégation')]
    private ?string $nomchef = null;

    #[ORM\Column(length: 14)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le numéro de chef de délégation')]
    private ?string $numero = null;
    
    #[ORM\Column(length: 60,nullable:true)]
    private ?string $email = null;


    #[ORM\Column(length:3)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le nombre de participants')]
    private ?int $nombreparticipant = null;

    #[ORM\Column(length:300, type: Types::TEXT, nullable:true)]
    private ?string $observation = null;

    #[ORM\ManyToOne(inversedBy: 'audiences')]
    
    private ?Communaute $communaute = null;

    #[ORM\ManyToOne(inversedBy: 'audiences')]
    #[Gedmo\Blameable(on: 'create')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(nullable:true)]
    private ?bool $statusaudience = null;

  


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $UpdatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $CreatedAt = null;
    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDaterencontre(): ?\DateTimeInterface
    {
        return $this->daterencontre;
    }

    public function setDaterencontre(\DateTimeInterface $daterencontre): self
    {
        $this->daterencontre = $daterencontre;

        return $this;
    }

    public function getNomchef(): ?string
    {
        return $this->nomchef;
    }

    public function setNomchef(string $nomchef): self
    {
        $this->nomchef = $nomchef;

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

    public function getNombreparticipant(): ?int
    {
        return $this->nombreparticipant;
    }

    public function setNombreparticipant(int $nombreparticipant): self
    {
        $this->nombreparticipant = $nombreparticipant;

        return $this;
    }

    

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(string $observation): self
    {
        $this->observation = $observation;

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

    public function isStatusaudience(): ?bool
    {
        return $this->statusaudience;
    }

    public function setStatusaudience(bool $statusaudience): self
    {
        $this->statusaudience = $statusaudience;

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

    public function __toString()
    {
        if (is_null($this->communaute)) {
            return 'NULL';
        }
        return $this->communaute;
    }
    

}
