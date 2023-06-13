<?php

namespace App\Controller\Configuration;

use App\Service\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/config/statistique')]
class   StatistiqueController extends AbstractController
{

    #[Route(path: '/', name: 'app_config_statistique_index', methods: ['GET', 'POST'])]
    public function index(Request $request, Breadcrumb $breadcrumb): Response
    {
        $modules = [
            [
                'label' => 'Parentrages',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_config_parametre_ls', ['module' => 'config'])
            ],

            [
                'label' => 'Audience',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_statistique', ['module' => 'blacklist'])
            ],
            [
                'label' => 'Don',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_statistique', ['module' => 'blacklist'])
            ],
            [
                'label' => 'Autres éléments',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_statistique', ['module' => 'blacklist'])
            ],
            [
                'label' => 'Graphiques',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_statistique', ['module' => 'graphiques'])
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

        return $this->render('config/statistique/liste.html.twig', [
            'modules' => $modules,
            'breadcrumb' => $breadcrumb
        ]);
    }


    #[Route(path: '/{module}', name: 'app_config_statistique_ls', methods: ['GET', 'POST'])]
    public function liste(Request $request, string $module): Response
    {
        /**
         * @todo: A déplacer dans un service
         */
        $parametes = [

            'don' => [
                     
                     'label' => 'Liste des dont',
                     'id' => 'param_hhh',
                     'href' => $this->generateUrl('app_gestion_don_index')
                   ] ,
            'graphiques' => [
                    [
                    'label' => 'Effectifs par statut',
                    'id' => 'chart_statut',
                    'icon' => 'bi bi-list',
                    'href' => $this->generateUrl('app_rh_dashboard_type_contrat_data')
                    ],
                    [
                        'label' => 'Effectifs par sexe',
                        'id' => 'chart_genre',
                        'icon' => 'bi bi-list',
                        'href' => $this->generateUrl('app_rh_dashboard_type_contrat_data')
                    ],
                    [
                        'label' => 'Effectifs par niveau hierarchique et Sexe',
                        'id' => 'chart_h_sexe',
                        'icon' => 'bi bi-list',
                        'href' => $this->generateUrl('app_rh_dashboard_type_contrat_data')
                    ],
                    [
                        'label' => 'Evolution des effectifs par type de contrat',
                        'id' => 'chart_contrat',
                        'icon' => 'bi bi-list',
                        'href' => $this->generateUrl('app_rh_dashboard_type_contrat_data')
                    ],
                    [
                        'label' => 'Pyramide des âges',
                        'id' => 'chart_py_age',
                        'icon' => 'bi bi-list',
                        'href' => $this->generateUrl('app_rh_dashboard_type_contrat_data')
                    ],
                    [
                        'label' => 'Pyramide des anciennetés',
                        'id' => 'chart_py_anc',
                        'icon' => 'bi bi-list',
                        'href' => $this->generateUrl('app_rh_dashboard_type_contrat_data')
                    ],
                    [
                        'label' => 'Effectifs par sexe et par niveau de maîtrise',
                        'id' => 'chart_maitrise',
                        'icon' => 'bi bi-list',
                        'href' => $this->generateUrl('app_rh_dashboard_type_contrat_data')
                    ]
                    
               ] ,

               'config' => [
                [
                    'label' => 'Catétgories',
                    'id' => 'param_categorie',
                    'href' => $this->generateUrl('app_parametre_categorie_index')
                ] ,
                [
                    'label' => 'Localités',
                    'id' => 'param_localite',
                    'href' => $this->generateUrl('app_parametre_localite_index')
                ],
                [
                    'label' => 'Communautés',
                    'id' => 'param_communaute',
                    'href' => $this->generateUrl('app_parametre_communaute_index')
                ],
                [
                    'label' => 'Types de dons',
                    'id' => 'param_typedon',
                    'href' => $this->generateUrl('app_parametre_typedon_index')
                ]

            ],

        ];


        return $this->render('config/statistique/liste.html.twig', 
        ['links' => $parametes[$module] ?? []]);
     
    }


    #[Route('/data-type-contrat', name: 'app_rh_dashboard_type_contrat_data', condition: "request.query.has('filters')")]
    public function dataTypeContrat(Request $request)
    {

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
