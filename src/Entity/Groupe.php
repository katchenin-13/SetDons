<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[ORM\Table(name:'user_groupe')]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'groupeUser', targetEntity: ModuleGroupePermition::class ,orphanRemoval: true, cascade:['persist'])]
    private Collection $moduleGroupePermitions;

    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: Utilisateur::class)]
    private Collection $utilisateurs;

    public function __construct()
    {
        $this->moduleGroupePermitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }


    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }


    public function setRoles(array $roles): self
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }
        return $this;
    }

   
    public function addRole($role)
    {
        $role = strtoupper($role);
        if (!$this->hasRole($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

   
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->roles, true);
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
            $moduleGroupePermition->setGroupeUser($this);
        }

        return $this;
    }

    public function removeModuleGroupePermition(ModuleGroupePermition $moduleGroupePermition): self
    {
        if ($this->moduleGroupePermitions->removeElement($moduleGroupePermition)) {
            // set the owning side to null (unless already changed)
            if ($moduleGroupePermition->getGroupeUser() === $this) {
                $moduleGroupePermition->setGroupeUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            $utilisateur->setGroupe($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getGroupe() === $this) {
                $utilisateur->setGroupe(null);
            }
        }

        return $this;
    }
}
