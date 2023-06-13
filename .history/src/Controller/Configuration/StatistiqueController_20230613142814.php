<?php

namespace App\Controller\Configuration;

use App\Service\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/config/statistique')]
class StatistiqueController extends AbstractController
{

    #[Route(path: '/', name: 'app_config_statistique_index', methods: ['GET', 'POST'])]
    public function index(Request $request, Breadcrumb $breadcrumb): Response
    {
        $modules = [
            [
                'label' => 'Général',
                'icon' => 'bi bi-list',
                'href' => $this->generateUrl('app_config_statistique_ls', ['module' => 'config'])
            ],
            [
                'label' => 'Ressource humaine',
                'icon' => 'bi bi-truck',
                'href' => $this->generateUrl('app_config_statistique_ls', ['module' => 'rh'])
            ],
            [
                'label' => 'Gestion des accès',
                'icon' => 'bi bi-users',
                'href' => $this->generateUrl('app_config_statistique_ls', ['module' => 'utilisateur'])
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

        return $this->render('config/statisti/index.html.twig', [
            'modules' => $modules,
            'breadcrumb' => $breadcrumb
        ]);
    }


    #[Route(path: '/{module}', name: 'app_config_statisti_ls', methods: ['GET', 'POST'])]
    public function liste(Request $request, string $module): Response
    {
        /**
         * @todo: A déplacer dans un service
         */
        $parametres = [



            // 'rh'=>[
            //     [
            //         'label' => 'Fonction',
            //         'id' => 'param_categorie',
            //         'href' => $this->generateUrl('app_parametre_fonction_index')
            //     ],
            //     [
            //         'label' => 'Entreprise',
            //         'id' => 'param_entreprise',
            //         'href' => $this->generateUrl('app_parametre_entreprise_index')
            //     ],
            //     [
            //         'label' => 'Direction',
            //         'id' => 'param_direction',
            //         'href' => $this->generateUrl('app_parametre_service_index')
            //     ],

            //     [
            //         'label' => 'Employé',
            //         'id' => 'param_client',
            //         'href' => $this->generateUrl('app_utilisateur_employe_index')
            //     ],
              /*  [
                    'label' => 'Fournisseur',
                    'id' => 'param_fournisseur',
                    'href' => $this->generateUrl('app_rh_fournisseur_index')
                ],*/


            // ],

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


        return $this->render('config/parametre/liste.html.twig', ['links' => $parametres[$module] ?? []]);
    }
}