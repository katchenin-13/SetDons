<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(['username'], message: 'Ce pseudo est déjà utilisé')]
#[ORM\Table(name:'user_utilisateur')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface, EquatableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un pseudo')]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez renseigner le mot de passe', groups: ['Registration'])]
    private ?string $password = null;

    #[ORM\OneToOne(inversedBy: "utilisateur", cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez sélectionner un employé', groups: ['Registration'])]
    private ?Employe $employe = null;

    #[ORM\ManyToMany(targetEntity: Groupe::class, inversedBy: 'utilisateurs')]
    #[ORM\JoinTable(name: 'user_utilisateur_groupe')]
    private Collection $groupes;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Categorie::class)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Typedon::class)]
    private Collection $typedons;

    
    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Communaute::class)]
    private Collection $communautes;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: PointFocal::class)]
    private Collection $pointFocals;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Beneficiaire::class)]
    private Collection $beneficiaires;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Don::class)]
    private Collection $dons;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Fieldon::class)]
    private Collection $fieldons;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Mission::class)]
    private Collection $missions;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Rapportmission::class)]
    private Collection $rapportmissions;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Agenda::class)]
    private Collection $agendas;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Contact::class)]
    private Collection $contacts;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Audience::class)]
    private Collection $audiences;

    #[ORM\OneToMany(mappedBy: 'Utilisateur', targetEntity: Localite::class)]
    private Collection $localites;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->typedons = new ArrayCollection();
        $this->communautes = new ArrayCollection();
        $this->pointFocals = new ArrayCollection();
        $this->beneficiaires = new ArrayCollection();
        $this->dons = new ArrayCollection();
        $this->fieldons = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->rapportmissions = new ArrayCollection();
        $this->agendas = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->audiences = new ArrayCollection();
        $this->localites = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = (array)$this->roles;
        $roles[] = 'ROLE_USER';
        foreach ($this->getGroupes() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }       

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * {@inheritDoc}
     */
    public function isEqualTo(UserInterface $user): bool
    {
        return $this->getUsername() == $user->getUserIdentifier();
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }



    public function hasRoleOnModuleChild($module, $child)
    {
        $module = strtoupper($module);
        $child = strtoupper($child);
        $result = false;
        foreach ($this->getRoles() as $role) {
            if (preg_match("/^ROLE_([A-Z_]+)_{$module}_([A-Z_]+)_{$child}/", $role)) {
                $result = true;
                break;
            }
        }
        return $this->hasRole('ROLE_ADMIN') || $result;
    }


    public function hasRoleOnAlias($module, $alias, $roleName)
    {
        $roleAlias = strtoupper(strtr($alias, '.', '_'));
        $role = "{$roleName}_{$module}_{$roleAlias}";
       
        return $this->hasRole('ROLE_ADMIN') || $this->hasRole($role);
    }


    public function hasRoleNameOnModuleChild($roleName, $module, $child)
    {
        $module = strtoupper($module);
        $child = strtoupper($child);
        $roleName = strtoupper($roleName);
        $result = false;
       
        foreach ($this->getRoles() as $role) {
            $regex = "^ROLE_{$roleName}_{$module}_([A-Z_]+)";
            if ($child) {
                $regex .= "_{$child}";
            }

            if (preg_match("/{$regex}/", $role)) {
                $result = true;
                break;
            }
        }
        return $this->hasRole('ROLE_ADMIN') || $result;
    }


    public function hasRoleOnModuleController($module,  $controller)
    {
        $module = strtoupper($module);
        $controller = strtoupper($controller);
        $result = false;
        foreach ($this->getRoles() as $role) {
            if (preg_match("/^ROLE_([A-Z_]+)_{$module}_{$controller}/", $role)) {
                $result = true;
                break;
            }
        }
        return $this->hasRole('ROLE_ADMIN') || $result;
    }


    public function hasRoleOnModuleControllers($module,  array $controllers)
    {
        $module = strtoupper($module);
        $controllers = array_map(function ($controller) {
            return strtoupper($controller);
        }, $controllers);
        $lsControllers = implode('|', $controllers);
        $result = false;
        foreach ($this->getRoles() as $role) {
            if (preg_match("/^ROLE_([A-Z_]+)_{$module}_({$lsControllers})/", $role)) {
                $result = true;
                break;
            }
        }
        return $this->hasRole('ROLE_ADMIN') || $result;
    }
  


    public function hasAllRoleOnModule($roleName, $module, $controller, $child = null, $as = null)
    {
        $module = strtoupper($module);
        
        $roleName = strtoupper($roleName);
        $controller = $as ? strtoupper($as) : strtoupper($controller);
        $result = false;

        
       
       
        foreach ($this->getRoles() as $role) {
            $regex = "^ROLE_{$roleName}_{$module}_{$controller}";
            
            if ($child) {
                $regex .= strtoupper("_{$child}");
            }
            if (preg_match("/{$regex}$/", $role)) {
                $result = true;
                break;
            }
        }
        return $this->hasRole('ROLE_ADMIN') || $result;
    }


    
    public function hasRoleStartsWith($roleName)
    {
        $result = false;
       
        foreach ($this->getRoles() as $role) {
            if (preg_match("/^{$roleName}/", $role, $matches)) {
                $result = true;
                break;
            }
        }
        return $this->hasRole('ROLE_ADMIN') || $result;
    }

    public function hasRoleOnModule(string $module, $exclude = null, $append = null)
    {
        $module = strtoupper($module);
        $result = false;

        $exclude = (array)$exclude;
        
       
        foreach ($this->getRoles() as $role) {
            $regex = "/^ROLE_([A-Z_]+)_{$module}_";

           // dd($regex);
            if ($append) {
                $regex .= strtoupper($append);
            }
            $regex .= "/";
            
            if (preg_match($regex, $role, $matches)) {
                $lowerMatch = strtolower($matches[1]);
                if (!$exclude || ($exclude &&  !in_array($lowerMatch, $exclude))) {
                    $result = true;
                    break;
                }
            }
        }
        return $this->hasRole('ROLE_ADMIN') || $result;
    }


     /**
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === 'ROLE_USER') {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

  

     /**
     * @param $roles
     */
    public function hasRoles($roles)
    {
        return array_intersect($this->getRoles(), $roles);
    }


    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes ?: $this->groupes = new ArrayCollection();
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeUtilisateur($this);
        }

        return $this;
    }


    public function getNomComplet()
    {
        return $this->getEmploye() ? $this->getEmploye()->getNomComplet(): '';
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getUtilisateur() === $this) {
                $category->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Typedon>
     */
    public function getTypedons(): Collection
    {
        return $this->typedons;
    }

    public function addTypedon(Typedon $typedon): self
    {
        if (!$this->typedons->contains($typedon)) {
            $this->typedons->add($typedon);
            $typedon->setUtilisateur($this);
        }

        return $this;
    }

    public function removeTypedon(Typedon $typedon): self
    {
        if ($this->typedons->removeElement($typedon)) {
            // set the owning side to null (unless already changed)
            if ($typedon->getUtilisateur() === $this) {
                $typedon->setUtilisateur(null);
            }
        }

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
            $communaute->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommunaute(Communaute $communaute): self
    {
        if ($this->communautes->removeElement($communaute)) {
            // set the owning side to null (unless already changed)
            if ($communaute->getUtilisateur() === $this) {
                $communaute->setUtilisateur(null);
            }
        }

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
            $pointFocal->setUtilisateur($this);
        }

        return $this;
    }

    public function removePointFocal(PointFocal $pointFocal): self
    {
        if ($this->pointFocals->removeElement($pointFocal)) {
            // set the owning side to null (unless already changed)
            if ($pointFocal->getUtilisateur() === $this) {
                $pointFocal->setUtilisateur(null);
            }
        }

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
            $beneficiaire->setUtilisateur($this);
        }

        return $this;
    }

    public function removeBeneficiaire(Beneficiaire $beneficiaire): self
    {
        if ($this->beneficiaires->removeElement($beneficiaire)) {
            // set the owning side to null (unless already changed)
            if ($beneficiaire->getUtilisateur() === $this) {
                $beneficiaire->setUtilisateur(null);
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
            $don->setUtilisateur($this);
        }

        return $this;
    }

    public function removeDon(Don $don): self
    {
        if ($this->dons->removeElement($don)) {
            // set the owning side to null (unless already changed)
            if ($don->getUtilisateur() === $this) {
                $don->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fieldon>
     */
    public function getFieldons(): Collection
    {
        return $this->fieldons;
    }

    public function addFieldon(Fieldon $fieldon): self
    {
        if (!$this->fieldons->contains($fieldon)) {
            $this->fieldons->add($fieldon);
            $fieldon->setUtilisateur($this);
        }

        return $this;
    }

    public function removeFieldon(Fieldon $fieldon): self
    {
        if ($this->fieldons->removeElement($fieldon)) {
            // set the owning side to null (unless already changed)
            if ($fieldon->getUtilisateur() === $this) {
                $fieldon->setUtilisateur(null);
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
            $mission->setUtilisateur($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getUtilisateur() === $this) {
                $mission->setUtilisateur(null);
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
            $rapportmission->setUtilisateur($this);
        }

        return $this;
    }

    public function removeRapportmission(Rapportmission $rapportmission): self
    {
        if ($this->rapportmissions->removeElement($rapportmission)) {
            // set the owning side to null (unless already changed)
            if ($rapportmission->getUtilisateur() === $this) {
                $rapportmission->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Agenda>
     */
    public function getAgendas(): Collection
    {
        return $this->agendas;
    }

    public function addAgenda(Agenda $agenda): self
    {
        if (!$this->agendas->contains($agenda)) {
            $this->agendas->add($agenda);
            $agenda->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAgenda(Agenda $agenda): self
    {
        if ($this->agendas->removeElement($agenda)) {
            // set the owning side to null (unless already changed)
            if ($agenda->getUtilisateur() === $this) {
                $agenda->setUtilisateur(null);
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
            $contact->setUtilisateur($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getUtilisateur() === $this) {
                $contact->setUtilisateur(null);
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
            $audience->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAudience(Audience $audience): self
    {
        if ($this->audiences->removeElement($audience)) {
            // set the owning side to null (unless already changed)
            if ($audience->getUtilisateur() === $this) {
                $audience->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Localite>
     */
    public function getLocalites(): Collection
    {
        return $this->localites;
    }

    public function addLocalite(Localite $localite): self
    {
        if (!$this->localites->contains($localite)) {
            $this->localites->add($localite);
            $localite->setUtilisateur($this);
        }

        return $this;
    }

    public function removeLocalite(Localite $localite): self
    {
        if ($this->localites->removeElement($localite)) {
            // set the owning side to null (unless already changed)
            if ($localite->getUtilisateur() === $this) {
                $localite->setUtilisateur(null);
            }
        }

        return $this;
    }

    }
