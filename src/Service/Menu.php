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
            'module'=>'module',
            'agenda'=>'agenda',
            'typeClient'=>'typeClient',
            'groupe'=>'groupe',
            'parent'=>'parent',
            'parametre'=>'parametre',
            'typeSociete'=>'typeSociete',
            'acteConstitution'=>'acteConstitution',
            'dossierConstition'=>'dossierConstition',
            'user'=>'user',
            'app_utilisateur_user_groupe_index'=>'app_utilisateur_user_groupe_index',
            'employe'=>'employe',
            'categorie'=>'categorie',
            'dossierActeVente'=>'dossierActeVente',
            'acte'=>'acte',
            'typeActe'=>'typeActe',
            'client'=>'client',
            'workflow'=>'workflow',
            'courierArrive'=>'courierArrive',
            'courierDepart'=>'courierDepart',
            'courierInterne'=>'courierInterne',
            'calendar'=>'calendar',
            'documentTypeActe'=>'documentTypeActe',
        ];

        return $array ;
    }
//    public function verifyanddispatch() {
//
//
//
//    }
}