<?php

namespace App\Controller\Gestion;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use App\Service\ActionRender;
use App\Service\FormError;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Dompdf\Dompdf;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/gestion/mission')]
class MissionController extends AbstractController
{


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $pdf = FDF;

        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'Hello World!');

        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'
        ));
    }

    #[Route('/pdf/generator/mission', name: 'app_pdf_generator_mision')]
    public function generatePdf(MissionRepository $mission): Response
    {
        $data = $mission->findAll();
        $html =  $this->renderView('gestion/mission/detail.html.twig', [
            'data' => $data
        ]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        return new Response(
            $dompdf->stream("resume", ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }

    #[Route('/', name: 'app_gestion_mission_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
            ->add('code', TextColumn::class, ['label' => 'code de la mission'])
            ->add('debut', DateTimeColumn::class, [
                'label' => 'Date de debut ',
                "format" => 'Y-m-d'
            ])
            ->add('datefin', DateTimeColumn::class, [
                'label' => 'Date de fin ',
                "format" => 'Y-m-d'
            ])
            ->add('ordremission', TextColumn::class, ['label' => 'Titre de la mission'])
            ->add('chefmission', TextColumn::class, ['label' => 'Chef de Mission'])
            ->add('communaute', TextColumn::class, ['label' => 'Communaute', 'field' =>  'comm.libelle'])
            ->add('objectif', TextColumn::class, ['label' => 'Objectif (s) de la mission'])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Mission::class,
            'query' => function (ORMQueryBuilder $req) {
                $req->select('m,comm')
                    ->from(Mission::class, 'm')
                    ->join('m.communaute', 'comm')
                    //  ->join('d.fieldon','f')

                ;
            }
        ])
        ->setName('dt_app_gestion_mission');

        $renders = [
            'show' =>  new ActionRender(function () {
                return true;
            }),
            'edit' =>  new ActionRender(function () {
                return true;
            }),
            'delete' => new ActionRender(function () {
                return true;
            }),
        ];


        $hasActions = false;

        foreach ($renders as $_ => $cb) {
            if ($cb->execute()) {
                $hasActions = true;
                break;
            }
        }

        if ($hasActions) {
            $table->add('id', TextColumn::class, [
                'label' => 'Actions'
                , 'orderable' => false
                ,'globalSearchable' => false
                ,'className' => 'grid_row_actions'
                , 'render' => function ($value, Mission $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [
                            'show' => [
                                'url' => $this->generateUrl('app_gestion_mission_show', ['id' => $value])
                                , 'ajax' => true
                                , 'icon' => '%icon% bi bi-eye'
                                , 'attrs' => ['class' => 'btn-success']
                                , 'render' => $renders['edit']
                            ],

                            
                            'edit' => [
                            'url' => $this->generateUrl('app_gestion_mission_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_gestion_mission_delete', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-trash'
                            , 'attrs' => ['class' => 'btn-danger']
                            ,  'render' => $renders['delete']
                        ]
                    ]

                    ];
                    return $this->renderView('_includes/default_actions.html.twig', compact('options', 'context'));
                }
            ]);
        }


        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }


        return $this->render('gestion/mission/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_gestion_mission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MissionRepository $missionRepository, FormError $formError): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_mission_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_mission_index');




            if ($form->isValid()) {

                $missionRepository->save($mission, true);
                $data = true;
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);


            } else {
                $message = $formError->all($form);
                $statut = 0;
                $statutCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                if (!$isAjax) {
                  $this->addFlash('warning', $message);
                }

            }


            if ($isAjax) {
                return $this->json( compact('statut', 'message', 'redirect', 'data'), $statutCode);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect, Response::HTTP_OK);
                }
            }


        }

        return $this->renderForm('gestion/mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_gestion_mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('gestion/mission/show.html.twig', [
            'mission' => $mission,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestion_mission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission, MissionRepository $missionRepository, FormError $formError): Response
    {

        $form = $this->createForm(MissionType::class, $mission, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_mission_edit', [
                    'id' =>  $mission->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_mission_index');


            if ($form->isValid()) {

                $missionRepository->save($mission, true);
                $data = true;
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);


            } else {
                $message = $formError->all($form);
                $statut = 0;
                $statutCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                if (!$isAjax) {
                  $this->addFlash('warning', $message);
                }

            }


            if ($isAjax) {
                return $this->json( compact('statut', 'message', 'redirect', 'data'), $statutCode);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect, Response::HTTP_OK);
                }
            }
        }

        return $this->renderForm('gestion/mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_gestion_mission_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Mission $mission, MissionRepository $missionRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_gestion_mission_delete'
                ,   [
                        'id' => $mission->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $missionRepository->remove($mission, true);

            $redirect = $this->generateUrl('app_gestion_mission_index');

            $message = 'Opération effectuée avec succès';

            $response = [
                'statut'   => 1,
                'message'  => $message,
                'redirect' => $redirect,
                'data' => $data
            ];

            $this->addFlash('success', $message);

            if (!$request->isXmlHttpRequest()) {
                return $this->redirect($redirect);
            } else {
                return $this->json($response);
            }
        }

        return $this->renderForm('gestion/mission/delete.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }
}
