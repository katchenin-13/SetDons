<?php

namespace App\Controller\Configuration\Statistique;

use App\Entity\Communaute;
use App\Repository\CommunauteRepository;
use App\Service\StatsService;
use App\Service\Utils;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboadController extends AbstractController
{
    #[Route('/statut', name: 'app_rh_dashboard_statut')]
    public function indexStatut(Request $request, EmployeRepository $employeRepository)
    {
        return $this->render('config/statistique/statut.html.twig');
    }



    #[Route('/data-statut', name: 'app_rh_dashboard_statut_data')]
    public function dataStatut(Request $request, EmployeRepository $employeRepository)
    {
        $all = $request->query->all();
        $filters = $all['filters'] ?? [];
        $totalGlobal = $employeRepository->countAll($filters);

        $data = $employeRepository->getStatusData($filters);
        $results = [];
        foreach ($data as $row) {
            $total = ($row['total'] / $totalGlobal) * 100;
            $results[] = [
                'name' => $row['libelle'],
                'y' => round($total),
                'value' => $row['total'],
                'drilldown' => null
            ];
        }
        return $this->json($results);
    }



    #[Route('/genre', name: 'app_rh_dashboard_genre')]
    public function indexGenre(Request $request, EmployeRepository $employeRepository)
    {
        return $this->render('config/statistique/genre.html.twig');
    }


    #[Route('/data-genre', name: 'app_rh_dashboard_genre_data')]
    public function dataGenre(Request $request, EmployeRepository $employeRepository)
    {
        $all = $request->query->all();
        $filters = $all['filters'] ?? [];

        $totalGlobal = $employeRepository->countAll($filters);

        $data = $employeRepository->getSexeData($filters);
        $results = [];
        foreach ($data as $row) {
            $total = ($row['total'] / $totalGlobal) * 100;
            $results[] = [
                'name' => $row['libelle'],
                'y' => round($total),
                'value' => $row['total'],
                'drilldown' => null
            ];
        }
        return $this->json($results);
    }

    #[Route('/hierarchie-sexe', name: 'app_rh_dashboard_hierarchie_sexe')]
    public function indexHierarchiqueSexe(Request $request, EmployeRepository $employeRepository, GenreRepository $genreRepository, NiveauHierarchiqueRepository $niveauHierarchiqueRepository)
    {
        return $this->render('config/statistique/hierarchie_sexe.html.twig');
    }

    #[Route('/data-hierarchie-sexe', name: 'app_rh_dashboard_hierarchie_sexe_data')]
    public function dataHierarchiqueSexe(Request $request, EmployeRepository $employeRepository, GenreRepository $genreRepository, NiveauHierarchiqueRepository $niveauHierarchiqueRepository)
    {
        $all = $request->query->all();
        $filters = $all['filters'] ?? [];
        $totalGlobal = $employeRepository->countAll($filters);



        $genres = $genreRepository->findAll();
        if (!empty($filters['genre'])) {
            $genres = $genreRepository->findBy(['id' => $filters['genre']]);
        }
        $niveaux = $niveauHierarchiqueRepository->findBy([], ['id' => 'ASC']);
        $xAxis = [];
        $allIds = array_map(function ($niveau) use (&$xAxis) {
            $xAxis[] = $niveau->getLibelle();
            return $niveau->getId();
        }, $niveaux);

        $results = [];
        $data = [];
        foreach ($genres as $genre) {
            $idGenre = $genre->getId();
            $filters['genre'] = $idGenre;
            $data[$idGenre] = $employeRepository->getNiveauHierarchiqueSexe($filters);
        }


        /**
         * ['id' => 1 , 'value']
         */

        //xAxis = [...liste_niveaux]
        /**
         * series : [{name: 'Feminin', data: [x0,y0,z0]}, {name: 'Masculin', data: [x1,y1,z1]}]
         */



        foreach ($data as $rows) {
            usort($rows, function ($a, $b) {
                return $a['_niveau_id'] <=> $b['_niveau_id'];
            });
        }


        foreach ($data as $idGenre => $rows) {
            $allNiveaux = [];
            foreach ($rows as $_row) {
                $currentNiveau = $_row['_niveau_id'];
                $allNiveaux[$currentNiveau] = $_row['_total'];
            }
            foreach ($allIds as $id) {
                if (!in_array($id, array_keys($allNiveaux))) {
                    $allNiveaux[$id] = 0;
                }
            }

            ksort($allNiveaux);



            $results[$idGenre] = array_values($allNiveaux);
        }

        $getLibelleGenre = function ($idGenre) use ($genreRepository) {
            return $genreRepository->find($idGenre);
        };

        $series = [];

        foreach ($results as $idGenre => $data) {
            $_genre = $getLibelleGenre($idGenre);
            $colors = ['M' => '#262626', 'F' => '#cf2e2e'];
            $series[] = ['name' => $_genre->getLibelle(), 'data' => $data, 'color' => $colors[$_genre->getCode()]];
        }

        return $this->json(['series' => $series, 'xAxis' => $xAxis]);
    }


    #[Route('/maitrise-sexe', name: 'app_rh_dashboard_maitrise_sexe')]
    public function indexMaitriseSexe(Request $request, EmployeRepository $employeRepository, GenreRepository $genreRepository, NiveauHierarchiqueRepository $niveauHierarchiqueRepository)
    {
        return $this->render('config/statistique/maitrise_sexe.html.twig');
    }

    #[Route('/data-maitrise-sexe', name: 'app_rh_dashboard_maitrise_sexe_data')]
    public function dataMaitriseSexe(Request $request, EmployeRepository $employeRepository, GenreRepository $genreRepository, NiveauMaitriseRepository $niveauMaitriseRepository)
    {
        $all = $request->query->all();
        $filters = $all['filters'] ?? [];
        $totalGlobal = $employeRepository->countAll($filters);



        $genres = $genreRepository->findAll();
        if (!empty($filters['genre'])) {
            $genres = $genreRepository->findBy(['id' => $filters['genre']]);
        }
        $niveaux = $niveauMaitriseRepository->findBy([], ['id' => 'ASC']);
        $xAxis = [];
        $allIds = array_map(function ($niveau) use (&$xAxis) {
            $xAxis[] = $niveau->getLibelle();
            return $niveau->getId();
        }, $niveaux);

        $results = [];
        foreach ($genres as $genre) {
            $idGenre = $genre->getId();
            $filters['genre'] = $idGenre;
            $data[$idGenre] = $employeRepository->getMaitriseSexe($filters);
        }


        /**
         * ['id' => 1 , 'value']
         */

        //xAxis = [...liste_niveaux]
        /**
         * series : [{name: 'Feminin', data: [x0,y0,z0]}, {name: 'Masculin', data: [x1,y1,z1]}]
         */



        foreach ($data as $rows) {
            usort($rows, function ($a, $b) {
                return $a['_niveau_id'] <=> $b['_niveau_id'];
            });
        }


        foreach ($data as $idGenre => $rows) {
            $allNiveaux = [];
            foreach ($rows as $_row) {
                $currentNiveau = $_row['_niveau_id'];
                $allNiveaux[$currentNiveau] = $_row['_total'];
            }
            foreach ($allIds as $id) {
                if (!in_array($id, array_keys($allNiveaux))) {
                    $allNiveaux[$id] = 0;
                }
            }

            ksort($allNiveaux);



            $results[$idGenre] = array_values($allNiveaux);
        }

        $getLibelleGenre = function ($idGenre) use ($genreRepository) {
            return $genreRepository->find($idGenre);
        };

        $series = [];

        foreach ($results as $idGenre => $data) {
            $_genre = $getLibelleGenre($idGenre);
            $colors = ['M' => '#262626', 'F' => '#cf2e2e'];
            $series[] = ['name' => $_genre->getLibelle(), 'data' => $data, 'color' => $colors[$_genre->getCode()]];
        }

        return $this->json(['series' => $series, 'xAxis' => $xAxis]);
    }


    #[Route('/pyramide-age', name: 'app_rh_dashboard_pyramide_age')]
    public function indexPyramideAge(Request $request, EmployeRepository $employeRepository, GenreRepository $genreRepository, NiveauHierarchiqueRepository $niveauHierarchiqueRepository)
    {
        return $this->render('config/statistique/pyramide_age.html.twig');
    }



    #[Route('/pyramide-age-data', name: 'app_rh_dashboard_pyramide_age_data')]
    public function dataPyramideAge(Request $request, EmployeRepository $employeRepository, GenreRepository $genreRepository, NiveauHierarchiqueRepository $niveauHierarchiqueRepository)
    {
        $all = $request->query->all();
        $filters = $all['filters'] ?? [];

        $tranches =  ['16-24', '25-35', '36-44', '45+'];


        $genres = $genreRepository->findBy([], ['id' => 'ASC']);

        $results = [];
        foreach ($genres as $genre) {
            $idGenre = $genre->getId();
            $filters['genre'] = $idGenre;
            $data[$idGenre] = $employeRepository->getPyramideAge($filters);
        }


        /**
         * ['id' => 1 , 'value']
         */

        //xAxis = [...liste_niveaux]
        /**
         * series : [{name: 'Feminin', data: [x0,y0,z0]}, {name: 'Masculin', data: [x1,y1,z1]}]
         */



        foreach ($data as $rows) {
            usort($rows, function ($a, $b) {
                return $a['tranche_age'] <=> $b['tranche_age'];
            });
        }

        $index = 0;

        foreach ($data as $idGenre => $rows) {

            $allTranches = [];
            foreach ($rows as $_row) {
                $currentTranche = $_row['tranche_age'];
                $allTranches[$currentTranche] = $index == 0 ? -$_row['_total'] : $_row['_total'];
            }
            foreach ($tranches as $tranche) {
                if (!isset($allTranches[$tranche])) {
                    $allTranches[$tranche] = 0;
                }
            }

            ksort($allTranches);


            $results[$idGenre] = array_values($allTranches);
            $index += 1;
        }


        $getLibelleGenre = function ($idGenre) use ($genreRepository) {
            return $genreRepository->find($idGenre);
        };

        $series = [];

        foreach ($results as $idGenre => $data) {
            $_genre = $getLibelleGenre($idGenre);
            $colors = ['M' => '#262626', 'F' => '#cf2e2e'];
            $series[] = ['name' => $_genre->getLibelle(), 'data' => $data, 'color' => $colors[$_genre->getCode()]];
        }

        return $this->json(['series' => $series, 'tranches' => $tranches]);
    }




    #[Route('/pyramide-anc', name: 'app_rh_dashboard_pyramide_anc')]
    public function indexPyramideAnciennete(Request $request, EmployeRepository $employeRepository, GenreRepository $genreRepository, NiveauHierarchiqueRepository $niveauHierarchiqueRepository)
    {
        return $this->render('config/statistique/pyramide_anc.html.twig');
    }



    #[Route('/pyramide-anc-data', name: 'app_rh_dashboard_pyramide_anc_data')]
    public function dataPyramideAnciennete(Request $request, EmployeRepository $employeRepository, GenreRepository $genreRepository, NiveauHierarchiqueRepository $niveauHierarchiqueRepository)
    {
        $all = $request->query->all();
        $filters = $all['filters'] ?? [];

        $tranches =   ['0-5', '6-15', '16+'];


        $genres = $genreRepository->findBy([], ['id' => 'ASC']);

        $results = [];
        foreach ($genres as $genre) {
            $idGenre = $genre->getId();
            $filters['genre'] = $idGenre;
            $data[$idGenre] = $employeRepository->getPyramideAnciennete($filters);
        }




        /**
         * ['id' => 1 , 'value']
         */

        //xAxis = [...liste_niveaux]
        /**
         * series : [{name: 'Feminin', data: [x0,y0,z0]}, {name: 'Masculin', data: [x1,y1,z1]}]
         */



        foreach ($data as $rows) {
            usort($rows, function ($a, $b) {
                return $a['tranche_age'] <=> $b['tranche_age'];
            });
        }

        $index = 0;

        foreach ($data as $idGenre => $rows) {

            $allTranches = [];

            foreach ($rows as $_row) {
                $currentTranche = $_row['tranche_age'];
                $allTranches[$currentTranche] = $index == 0 ? -$_row['_total'] : $_row['_total'];
            }


            foreach ($tranches as $tranche) {
                if (!isset($allTranches[$tranche])) {
                    $allTranches[$tranche] = 0;
                }
            }



            ksort($allTranches);


            $results[$idGenre] = array_values($allTranches);
            $index += 1;
        }


        $getLibelleGenre = function ($idGenre) use ($genreRepository) {
            return $genreRepository->find($idGenre);
        };

        $series = [];

        foreach ($results as $idGenre => $data) {
            $_genre = $getLibelleGenre($idGenre);
            $colors = ['M' => '#262626', 'F' => '#cf2e2e'];
            $series[] = ['name' => $_genre->getLibelle(), 'data' => $data, 'color' => $colors[$_genre->getCode()]];
        }

        return $this->json(['series' => $series, 'tranches' => $tranches]);
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


    // #[Route('/type-contrat', name: 'app_rh_dashboard_type_contrat')]
    // public function indexTypeContrat()
    // {
    //     $formBuilder = $this->createFormBuilder()
    //         ->setAction($this->generateUrl('app_rh_dashboard_type_contrat'))
    //         ->setMethod('POST');

    //     $formBuilder->add('communaute', EntityType::class, [
    //         'placeholder' => '---',
    //         'choice_label' => 'libelle',
    //         'label' => 'Type de contrat',
    //         'attr' => ['class' => 'has-select2'],
    //         'choice_attr' => function (Communaute $communaute) {
    //             return ['data-value' => $communaute->getLibelle()];
    //         },
    //         'class' => Communaute::class,
    //         'required' => false
    //     ]);


    //     $form = $formBuilder->getForm();

    //     return $this->renderForm('config/statistique/type_contrat.html.twig', [
    //         'form' => $form
    //     ]);

   

    #[Route('/type-contrat', name: 'app_rh_dashboard_type_contrat')]
    public function indexTypeContrat()
    {
        $formBuilder = $this->createFormBuilder()
        ->setAction($this->generateUrl('c_type_contrat'))
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

        return $this->renderForm('config/statistique/type_contrat.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/data-type-contrat', name: 'app_rh_dashboard_type_contrat_data', condition: "request.query.has('filters')")]
    public function dataTypeContrat(Request $request, StatsService $statsService)
    {
        $all = $request->query->all();
        $filters = $all['filters'] ?? [];
        $typeContratId = $filters['communaute'];
        $data =   $statsService->getAudienceMoisCom($typeContratId);

        $mois = [];
        $nbre = [];
        foreach ($data as $cam) {
            $mois[] = $cam['mois'];
            $nbre[] = $cam['nbre'];
        }

        return $this->json([
            'mois' => $mois,
            'nbre' => $nbre,

        ]);

        // return $this->render('config/statistique/type_contrat.html.twig', [
        //     'mois' => json_encode($mois),
        //     'nbre' => json_encode($nbre),
        //     //'sdata' => json_encode(["2 Jan", "9 Jan", "16 Jan", "23 Jan", "30 Jan", "6 Feb", "13 Feb"])

        // ]);
    }

  
    // #[Route('/data-type-contrat', name: 'app_rh_dashboard_type_contrat_data', condition: "request.query.has('filters')")]
    // public function dataTypeContrat(Request $request, EmployeRepository $employeRepository, TypeContratRepository $typeContratRepository)
    // {
    //     $all = $request->query->all();
    //     $filters = $all['filters'] ?? [];
    //     $typeContratId = $filters['typeContrat'];
    //     $dataAnnees = $employeRepository->getAnneeRangeContrat($typeContratId);
    //     $annees = range($dataAnnees['min_year'], $dataAnnees['max_year']);
    //     $data = $employeRepository->getDataTypeContrat($typeContratId);

    //     $typeContrat = $typeContratRepository->find($typeContratId);

    //     $series = [['name' => $typeContrat->getLibelle(), 'data' => []]];

    //     foreach ($data as $_row) {
    //         $series[0]['data'][] = $_row['_total'];
    //     }


    //     return $this->json(['series' => $series, 'annees' => $annees]);
    // }
   
}
