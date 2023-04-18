<?php

namespace App\Entity;

use App\Repository\GroupeModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeModuleRepository::class)]
class GroupeModule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\OneToMany(mappedBy: 'groupeModule', targetEntity: ModuleGroupePermition::class)]
    private Collection $moduleGroupePermitions;

    #[ORM\Column(length: 255)]
    private ?string $lien = null;

    public function __construct()
    {
        $this->moduleGroupePermitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
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

    /**
     * @return Collection<int, ModuleGroupePermition>
     */
    public function getModuleGroupePermitions(): Collection
    {
        return $this->moduleGroupePermitions;
    }

    public function addModuleGroupePermition(ModuleGroupePermition $moduleGroupePermition): self
    {
        if (!$this->moduleGroupePermitions->contains($moduleGroupePermition)) {
            $this->moduleGroupePermitions->add($moduleGroupePermition);
            $moduleGroupePermition->setGroupeModule($this);
        }

        return $this;
    }

    public function removeModuleGroupePermition(ModuleGroupePermition $moduleGroupePermition): self
    {
        if ($this->moduleGroupePermitions->removeElement($moduleGroupePermition)) {
            // set the owning side to null (unless already changed)
            if ($moduleGroupePermition->getGroupeModule() === $this) {
                $moduleGroupePermition->setGroupeModule(null);
            }
        }

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }
}
