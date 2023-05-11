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
            $menu->addChild('Audience', ['route' => 'app_gestion_audience_index', 'label' => 'Audience'])->setExtra('icon', 'bi bi-arrow-up-right-circle');
           $menu->addChild('Dons', ['route' => 'app_gestion_don_index', 'label' => 'Dons'])->setExtra('icon', 'bi bi-arrow-up-right-circle');
           $menu->addChild('Mission', ['route' => 'app_gestion_rapportmission_index', 'label' => 'Mission'])->setExtra('icon', 'bi bi-arrow-up-right-circle');
           $menu->addChild('Contact', ['route' => 'app_gestion_contact_index', 'label' => 'Contact'])->setExtra('icon', 'bi bi-arrow-up-right-circle');
           $menu->addChild('Agenda', ['route' =>'app_gestion_calendrier', 'label' => 'Agenda'])->setExtra('icon', 'bi bi-arrow-up-right-circle');
           $menu->addChild('Evenement', ['route' => 'app_config_agenda_index', 'label' => 'evenement'])->setExtra('icon', 'bi bi-arrow-up-right-circle');
        }         

       
        return $menu;
    }
}