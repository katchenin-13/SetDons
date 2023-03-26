<?php

namespace App\Menu;

use App\Entity\Utilisateur;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class DemandeMenuBuilder
{
    private $factory;
    private $security;
    /**
     * Undocumented variable
     *
     * @var Utilisateur
     */
    private $user;

    private const MODULE_NAME = 'demande';

    public function __construct(FactoryInterface $factory, Security $security)
    {
        $this->factory = $factory;
        $this->security = $security;
        $this->user = $security->getUser();
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setExtra('module', self::MODULE_NAME);
        if ($this->user->hasRoleOnModule(self::MODULE_NAME)) {
            $menu->addChild(self::MODULE_NAME, ['label' => 'Demande Absence']);
        }
        
        if (isset($menu[self::MODULE_NAME])) {
            $menu->addChild('absence', ['route' => 'app_demande_demande_index_avis', 'label' => 'Demande Absence'])->setExtra('icon', 'bi bi-gear');
            $menu->addChild('avis', ['route' => 'app_demande_demande_index_avis', 'label' => 'Affecter avis'])->setExtra('icon', 'bi bi-gear');
            $menu->addChild('user', ['route' => 'app_utilisateur_utilisateur_index', 'label' => 'Utilisateurs'])->setExtra('icon', 'bi bi-gear');

        }
       
        return $menu;
    }
}