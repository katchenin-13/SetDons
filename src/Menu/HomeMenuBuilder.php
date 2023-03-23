<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;


class HomeMenuBuilder
{
    private $factory;

    private const MODULE_NAME = 'home';

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
    
        $menu->addChild('dashboard', ['route' => 'app_default', 'label' => 'Tableau de bord'])
            ->setExtra('icon', 'bi bi-grid');
       
        return $menu;
    }
}