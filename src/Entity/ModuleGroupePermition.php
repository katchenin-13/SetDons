<?php

namespace App\Entity;

use App\Repository\ModuleGroupePermitionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleGroupePermitionRepository::class)]
class ModuleGroupePermition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'module')]
    private ?Permition $permition = null;

    #[ORM\ManyToOne(inversedBy: 'moduleGroupePermitions')]
    private ?Module $module = null;

    #[ORM\ManyToOne(inversedBy: 'moduleGroupePermitions')]
    private ?GroupeModule $groupeModule = null;

    #[ORM\ManyToOne(inversedBy: 'moduleGroupePermitions')]
    private ?Groupe $groupeUser = null;

    #[ORM\Column]
    private ?int $ordreGroupe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getPermition(): ?Permition
    {
        return $this->permition;
    }

    public function setPermition(?Permition $permition): self
    {
        $this->permition = $permition;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getGroupeModule(): ?GroupeModule
    {
        return $this->groupeModule;
    }

    public function setGroupeModule(?GroupeModule $groupeModule): self
    {
        $this->groupeModule = $groupeModule;

        return $this;
    }

    public function getGroupeUser(): ?Groupe
    {
        return $this->groupeUser;
    }

    public function setGroupeUser(?Groupe $groupeUser): self
    {
        $this->groupeUser = $groupeUser;

        return $this;
    }

    public function getOrdreGroupe(): ?int
    {
        return $this->ordreGroupe;
    }

    public function setOrdreGroupe(int $ordreGroupe): self
    {
        $this->ordreGroupe = $ordreGroupe;

        return $this;
    }

}
