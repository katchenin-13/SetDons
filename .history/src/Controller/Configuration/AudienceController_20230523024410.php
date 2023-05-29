<?php

namespace App\Controller\Configuration;

use App\Service\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/config/audience')]
class   AudienceController extends AbstractController
{

    #[Route(path: '/', name: 'app_config_audience_index', methods: ['GET', 'POST'])]
    public function index(Request $request, Breadcrumb $breadcrumb): Response
    {
        $modules = [
            [
                'label' => 'Liste des audiences( groupe de personnepersonnes )',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_gestion_audience_index', ['module' => 'audience'])
            ],

            [    
                'label' => 'Liste des audiences( personnes unique)',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_gestion_demande_index', ['module' => 'blacklist'])
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

        return $this->render('config/audience/index.html.twig', [
            'modules' => $modules,
            'breadcrumb' => $breadcrumb
        ]);
    }


    #[Route(path: '/{module}', name: 'app_config_audience_ls', methods: ['GET', 'POST'])]
    public function liste(Request $request, string $module): Response
    {
        /**
         * @todo: A déplacer dans un service
         */
        $parametes = [

            'audience' => [
                                'label' => 'Audience',
                                'id' => 'param_audience',
                                'href' => $this->generateUrl('app_gestion_audience_index')
                            ] ,
                
           'demande' => [
                     
                                'label' => 'Damandes Individuelles',
                                'id' => 'param_r',
                                'href' => $this->generateUrl('app_gestion_demande_index')
                        ] ,
        ];


        return $this->render('config/audience/liste.html.twig', ['links' => $parametes[$module] ?? []]);
    }
}