<?php

namespace App\Entity;

use App\Repository\PermitionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermitionRepository::class)]
class Permition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'permition', targetEntity: ModuleGroupePermition::class)]
    private Collection $module;

    public function __construct()
    {
        $this->module = new ArrayCollection();
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
     * @return Collection<int, ModuleGroupePermition>
     */
    public function getModule(): Collection
    {
        return $this->module;
    }

    public function addModule(ModuleGroupePermition $module): self
    {
        if (!$this->module->contains($module)) {
            $this->module->add($module);
            $module->setPermition($this);
        }

        return $this;
    }

    public function removeModule(ModuleGroupePermition $module): self
    {
        if ($this->module->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getPermition() === $this) {
                $module->setPermition(null);
            }
        }

        return $this;
    }
}
