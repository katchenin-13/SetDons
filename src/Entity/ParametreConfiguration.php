<?php

namespace App\Entity;

use App\Repository\ParametreConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParametreConfigurationRepository::class)]
class ParametreConfiguration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $primaryColor = null;

    #[ORM\Column(length: 255)]
    private ?string $secondaryColor = null;


    #[ORM\ManyToOne(inversedBy: 'parametreConfigurations')]
    private ?Entreprise $entreprise = null;

    #[ORM\ManyToOne(inversedBy: 'parametreConfigurations',cascade:["persist"], fetch:"EAGER")]
    private ?Fichier $logo = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrimaryColor(): ?string
    {
        return $this->primaryColor;
    }

    public function setPrimaryColor(string $primaryColor): self
    {
        $this->primaryColor = $primaryColor;

        return $this;
    }

    public function getSecondaryColor(): ?string
    {
        return $this->secondaryColor;
    }

    public function setSecondaryColor(string $secondaryColor): self
    {
        $this->secondaryColor = $secondaryColor;

        return $this;
    }


    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getLogo(): ?Fichier
    {
        return $this->logo;
    }

    public function setLogo(?Fichier $logo): self
    {
        $this->logo = $logo;

        return $this;
    }


}
