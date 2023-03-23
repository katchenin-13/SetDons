<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class RhMenuBuilder
{
    private $factory;
    private $security;
    /**
     * Undocumented variable
     *
     * @var \App\Entity\Utilisateur
     */
    private $user;

    private const MODULE_NAME = 'rh';

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
            $menu->addChild(self::MODULE_NAME, ['label' => 'Demandes']);
        }
        
        if (isset($menu[self::MODULE_NAME])) {
            //$menu->addChild('demande', ['route' => 'app_demande_demande_index', 'label' => 'Demande Absence'])->setExtra('icon', 'bi bi-arrow-up-right-circle');
           // $menu->addChild('avis', ['route' => 'app_demande_demande_index_avis', 'label' => 'Affecter avis'])->setExtra('icon', 'bi bi-arrow-up-right-circle');
        }
       
        return $menu;
    }
}