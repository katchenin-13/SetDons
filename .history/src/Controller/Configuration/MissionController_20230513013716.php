<?php

namespace App\Controller\Configuration;

use App\Service\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/config/don')]
class   MissionController extends AbstractController
{

    #[Route(path: '/', name: 'app_config_mission_index', methods: ['GET', 'POST'])]
    public function index(Request $request, Breadcrumb $breadcrumb): Response
    {
        $modules = [
            [
                'label' => 'Liste des dons',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_gestion_mission_index', ['module' => 'Gestion des missions'])
            ],
            
            [
                'label' => 'Liste des promesses',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_gestion_rapportmission_index', ['module' => 'Gestion des rapport mission'])
            ],



        ];

        $breadcrumb->addItem([
            [
                'route' => 'app_default',
                'label' => 'Tableau de bord'
            ],
            [
                'label' => 'Paramètres'
            ]
        ]);

        return $this->render('config/don/index.html.twig', [
            'modules' => $modules,
            'breadcrumb' => $breadcrumb
        ]);
    }


    #[Route(path: '/{module}', name: 'app_config_don_ls', methods: ['GET', 'POST'])]
    public function liste(Request $request, string $module): Response
    {
        /**
         * @todo: A déplacer dans un service
         */
        $parametes = [

            'don' => [
                     
                     'label' => 'Liste des missions',
                     'id' => 'param_hhh',
                     'href' => $this->generateUrl('app_gestion_mission_index')
                ] ,
            'blacklist' => [
                     
                    'label' => 'Liste des rapport misssio',
                    'id' => 'param_rrr',
                    'href' => $this->generateUrl('app_gestion_rapportmission_index')
                 
               ] ,
        ];


        return $this->render('config/don/liste.html.twig', ['links' => $parametes[$module] ?? []]);
    }
}