<?php

namespace App\Controller\Gestion;

use App\Entity\Rapportmission;
use App\Form\RapportmissionType;
use App\Repository\RapportmissionRepository;
use App\Service\ActionRender;
use App\Service\FormError;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestion/rapportmission')]
class RapportmissionController extends AbstractController
{
    #[Route('/', name: 'app_gestion_rapportmission_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
            ->add('libelle', TextColumn::class, ['label' => 'Titre de la mission'])
            ->add('chefmission', TextColumn::class, ['label' => 'Chef de Mission'])
            ->add('objectif', TextColumn::class, ['label' => 'Objectif (s) de la mission'])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Rapportmission::class,
        ])
        ->setName('dt_app_gestion_rapportmission');

        $renders = [
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
                , 'render' => function ($value, Rapportmission $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [
                            'edit' => [
                            'url' => $this->generateUrl('app_gestion_rapportmission_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_gestion_rapportmission_delete', ['id' => $value])
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


        return $this->render('gestion/rapportmission/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_gestion_rapportmission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RapportmissionRepository $rapportmissionRepository, FormError $formError): Response
    {
        $rapportmission = new Rapportmission();
        $form = $this->createForm(RapportmissionType::class, $rapportmission, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_rapportmission_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_rapportmission_index');




            if ($form->isValid()) {

                $rapportmissionRepository->save($rapportmission, true);
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

        return $this->renderForm('gestion/rapportmission/new.html.twig', [
            'rapportmission' => $rapportmission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_gestion_rapportmission_show', methods: ['GET'])]
    public function show(Rapportmission $rapportmission): Response
    {
        return $this->render('gestion/rapportmission/show.html.twig', [
            'rapportmission' => $rapportmission,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestion_rapportmission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rapportmission $rapportmission, RapportmissionRepository $rapportmissionRepository, FormError $formError): Response
    {

        $form = $this->createForm(RapportmissionType::class, $rapportmission, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_rapportmission_edit', [
                    'id' =>  $rapportmission->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_rapportmission_index');


            if ($form->isValid()) {

                $rapportmissionRepository->save($rapportmission, true);
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

        return $this->renderForm('gestion/rapportmission/edit.html.twig', [
            'rapportmission' => $rapportmission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_gestion_rapportmission_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Rapportmission $rapportmission, RapportmissionRepository $rapportmissionRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_gestion_rapportmission_delete'
                ,   [
                        'id' => $rapportmission->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $rapportmissionRepository->remove($rapportmission, true);

            $redirect = $this->generateUrl('app_gestion_rapportmission_index');

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

        return $this->renderForm('gestion/rapportmission/delete.html.twig', [
            'rapportmission' => $rapportmission,
            'form' => $form,
        ]);
    }
}
