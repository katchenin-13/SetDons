<?php

namespace App\Controller\Configuration;

use App\Entity\Communaute;
use App\Form\CommunauteType;
use App\Service\Breadcrumb;
use App\Service\Menu;
use App\Service\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/config/statistique')]
class StatistiqueController extends AbstractController
{
    private $menu;
    public function __construct(Menu $menu){
        $this->menu = $menu;



    }

    #[Route(path: '/', name: 'app_config_statistique_index', methods: ['GET', 'POST'])]
    public function index(Request $request, Breadcrumb $breadcrumb): Response
    {
       /* if($this->menu->getPermission()){
            $redirect = $this->generateUrl('app_default');
            return $this->redirect($redirect);
            //dd($this->menu->getPermission());
        }*/
        $modules = [
            [
                'label' => 'Parentrages',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_config_statistique_ls', ['module' => 'config'])
            ],

            [
                'label' => 'Audience',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_config_statistique_ls', ['module' => 'blacklist1'])
            ],
            [
                'label' => 'Don',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_config_statistique_ls', ['module' => 'blacklist2'])
            ],
            [
                'label' => 'Autres éléments',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_config_statistique_ls', ['module' => 'blacklist'])
            ],
            [
                'label' => 'Graphiques',
                'icon' => 'bi bi-people',
                'href' => $this->generateUrl('app_config_statistique_ls', ['module' => 'graphiques'])
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

        return $this->render('config/statistique/index.html.twig', [
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
        $parametres = [

    

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
                    'href' => $this->generateUrl('app_gestion_don_index')
                ],
                [
                    'label' => 'Evolution des effectifs par type de contrat',
                    'id' => 'chart_contrat',
                    'icon' => 'bi bi-list',
                    'href' => $this->generateUrl('app_gestion_don_index')
                ],
                [
                    'label' => 'Pyramide des âges',
                    'id' => 'chart_py_age',
                    'icon' => 'bi bi-list',
                    'href' => $this->generateUrl('app_gestion_don_index')
                ],
                [
                    'label' => 'Pyramide des anciennetés',
                    'id' => 'chart_py_anc',
                    'icon' => 'bi bi-list',
                    'href' => $this->generateUrl('app_gestion_don_index')
                ],
                [
                    'label' => 'Effectifs par sexe et par niveau de maîtrise',
                    'id' => 'chart_maitrise',
                    'icon' => 'bi bi-list',
                    'href' => $this->generateUrl('app_gestion_don_index')
                ]

            ],


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


        return $this->render('config/statistique/liste.html.twig', ['links' => $parametres[$module] ??[]]);
    }

   

    private function createFilterForm()
    {

        $formBuilder = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_config_statistique_index'))
            ->setMethod('POST');

        $formBuilder->add('tranche', ChoiceType::class, [
            'label' => 'Tranche d\'âge',
            'required' => false,
            'placeholder' => '---',
            'choices' => array_flip([
                '16_24' => '16-24',
                '25_35' => '25-35',
                '36_44' => '36-44',
                '45_99' => '45+'
            ])
        ]);
        $formBuilder->add('anciennete', ChoiceType::class, [
            'label' => 'Ancienneté',
            'required' => false,
            'placeholder' => '---',
            'choices' => array_flip([
                '0_5' => '0-5',
                '6_15' => '6-15',
                '16_99' => '16 et plus'
            ])
        ]);
        $formBuilder->add('annee', IntegerType::class, ['label' => 'Année']);
        $formBuilder->add('mois', ChoiceType::class, ['choices' => array_flip(Utils::MOIS), 'label' => 'Mois', 'attr' => ['class' => 'has-select2']]);
        $formBuilder->add('communaute', EntityType::class, [
            'placeholder' => '---',
            'choice_label' => 'libelle',
            'label' => 'Type de contrat',
            'class' => Communaute::class,
            'required' => false
        ]);
        $formBuilder->add('genre', EntityType::class, [
            'placeholder' => '---',
            'choice_label' => 'code',
            'label' => 'Sexe',
            'class' => Genre::class,
            'required' => false
        ]);
        $formBuilder->add('unite', EntityType::class, [
            'placeholder' => '---',
            'choice_label' => 'libelle',
            'label' => 'Unité',
            'class' => UniteEmploye::class,
            'required' => false
        ]);
        $formBuilder->add('niveauHierarchique', EntityType::class, [
            'placeholder' => '---',
            'choice_label' => 'libelle',
            'label' => 'Niveau Hiérarchique',
            'class' => NiveauHierarchique::class,
            'required' => false
        ]);
        $formBuilder->add('niveauMaitrise', EntityType::class, [
            'placeholder' => '---',
            'choice_label' => 'libelle',
            'label' => 'Niveau de maitrise',
            'class' => NiveauMaitrise::class,
            'required' => false
        ]);
        $formBuilder->add('statut', EntityType::class, [
            'placeholder' => '---',
            'choice_label' => 'libelle',
            'label' => 'Statut',
            'class' => StatutEmploye::class,
            'required' => false
        ]);

        return $formBuilder->getForm();
    }


    #[Route('/type-contrat', name: 'app_rh_dashboard_type_contrat')]
    public function indexTypeContrat(Request $request, EmployeRepository $employeRepository, GenreRepository $genreRepository, NiveauHierarchiqueRepository $niveauHierarchiqueRepository)
    {
        $formBuilder = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_rh_dashboard_type_contrat'))
            ->setMethod('POST');

        $formBuilder->add('communaute', EntityType::class, [
            'placeholder' => '---',
            'choice_label' => 'libelle',
            'label' => 'Type de contrat',
            'attr' => ['class' => 'has-select2'],
            'choice_attr' => function (Communaute $communaute) {
                return ['data-value' => $communaute->getLibelle()];
            },
            'class' => Communaute::class,
            'required' => false
        ]);


        $form = $formBuilder->getForm();

        return $this->renderForm('config/statistique//type_contrat.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/data-type-contrat', name: 'app_rh_dashboard_type_contrat_data', condition: "request.query.has('filters')")]
    public function dataTypeContrat(Request $request, EmployeRepository $employeRepository, TypeContratRepository $typeContratRepository)
    {
        $all = $request->query->all();
        $filters = $all['filters'] ?? [];
        $typeContratId = $filters['typeContrat'];
        $dataAnnees = $employeRepository->getAnneeRangeContrat($typeContratId);
        $annees = range($dataAnnees['min_year'], $dataAnnees['max_year']);
        $data = $employeRepository->getDataTypeContrat($typeContratId);

        $typeContrat = $typeContratRepository->find($typeContratId);

        $series = [['name' => $typeContrat->getLibelle(), 'data' => []]];

        foreach ($data as $_row) {
            $series[0]['data'][] = $_row['_total'];
        }


        return $this->json(['series' => $series, 'annees' => $annees]);
    }
}

   
