<?php

namespace App\Controller\Configuration;

use App\Service\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/config/don')]
class   DonController extends AbstractController
{

    #[Route(path: '/', name: 'app_config_don_index', methods: ['GET', 'POST'])]
    public function index(Request $request, Breadcrumb $breadcrumb): Response
    {
        $modules = [
            [
                'label' => 'Lieste des dons',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_gestion_don_index', ['module' => 'affectation'])
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

            'config' => [
                     
                     'label' => 'hfhffgg',
                     'id' => 'param_hhh',
                     'href' => $this->generateUrl('app_gestion_don_index')
                ] ,
                'rh' => [
                     
                    'label' => 'fjhhfhghhf',
                    'id' => 'param_rrr',
                    'href' => $this->generateUrl('app_gestion_don_index')
                 
               ] ,
        ];


        return $this->render('config/audience/liste.html.twig', ['links' => $parametes[$module] ?? []]);
    }
}