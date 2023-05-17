<?php

namespace App\Service;

use App\Entity\ModuleGroupePermition;
use App\Entity\ParametreConfiguration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

use Psr\Container\ContainerInterface;
use function PHPUnit\Framework\isEmpty;

class Menu
{

    private $em;
    private $route;
    private $container;
    private $security;

    private $resp;


    public function __construct(EntityManagerInterface $em, RequestStack $requestStack, RouterInterface $router,Security $security)
    {
        $this->security = $security;
       // dd($this->security->getUser()->getGroupe()->getId());
        $this->em = $em;
        if ($requestStack->getCurrentRequest()) {
            $this->route = $requestStack->getCurrentRequest()->attributes->get('_route');
            $this->container = $router->getRouteCollection()->all();
       

        //         if($this->getPermission()){
        //         $this->setResp("rrr");
        //         }
        //    //dd($this->getResp());
        //    if($this->getResp() == "rrr"){
        //         dd($this->getResp());
        //     }
        }
       // dd($this->getPermission());
       /* if(!$this->getPermission()){
            dd("rrrr");
        }*/
        //$this->getPermission();
    }

    public function listeModule()
    {
        $repo = $this->em->getRepository(ModuleGroupePermition::class)->afficheModule($this->security->getUser()->getGroupe()->getId());
        return $repo;
    }

    public function listeGroupeModule()
    {
        $repo = $this->em->getRepository(ModuleGroupePermition::class)->affiche($this->security->getUser()->getGroupe()->getId());

        return $repo;
    }

    public function findParametre()
    {
        $repo = $this->em->getRepository(ParametreConfiguration::class)->findOneBy(array('entreprise'=>$this->security->getUser()->getEmploye()->getEntreprise()));
       // dd($repo);
        return $repo;
    }
   
    public function getPermission()
    {
        $repo = $this->em->getRepository(ModuleGroupePermition::class)->getPermission($this->security->getUser()->getGroupe()->getId(),$this->route);
        // dd($repo);
       /* if($this->getPermission()){
            return ;
        }*/
        return $repo;
    }

    public function liste()
    {
        $repo = $this->em->getRepository(Groupe::class)->afficheGroupes();

        return $repo;
    }

    public function listeParent()
    {
        $repo = $this->em->getRepository(Groupe::class)->affiche();

        return $repo;
    }
//public function listeModule
    public function listeGroupe()
    {
        $array = [
            'app_config_parametre_index'=>'Parametre',
            'app_utilisateur_groupe_index'=>'Groupe utilisateur',
            'app_utilisateur_utilisateur_index'=>'Utilisateur',
            'app_gestion_audience_index'=>'Audience',
            'app_gestion_don_index'=>'Don',
            'app_gestion_contact_index'=>'Contact',
            'c'=>'Mission',
            'app_config_agenda_index'=>'Evènement',
            'app_gestion_calendrier'=>'Agenda',
            'app_gestion_admin_dashboad'=>'Statistique',
            
        ];

        return $array ;
    }
//    public function verifyanddispatch() {
//
//
//
//    }
}