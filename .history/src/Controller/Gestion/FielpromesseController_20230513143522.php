<?php

namespace App\Controller\Gestion;

use App\Entity\Fielpromesse;
use App\Form\FielpromesseType;
use App\Repository\FielpromesseRepository;
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

#[Route('/gestion/fielpromesse')]
class FielpromesseController extends AbstractController
{
    #[Route('/', name: 'app_gestion_fielpromesse_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
        ->createAdapter(ORMAdapter::class, [
            'entity' => Fielpromesse::class,
        ])
        ->setName('dt_app_gestion_fielpromesse');

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
                , 'render' => function ($value, Fielpromesse $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [
                            'edit' => [
                            'url' => $this->generateUrl('app_gestion_fielpromesse_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_gestion_fielpromesse_delete', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-trash'
                            , 'attrs' => ['class' => 'btn-main']
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


        return $this->render('gestion/fielpromesse/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_gestion_fielpromesse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FielpromesseRepository $fielpromesseRepository, FormError $formError): Response
    {
        $fielpromesse = new Fielpromesse();
        $form = $this->createForm(FielpromesseType::class, $fielpromesse, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_fielpromesse_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_fielpromesse_index');




            if ($form->isValid()) {

                $fielpromesseRepository->save($fielpromesse, true);
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

        return $this->renderForm('gestion/fielpromesse/new.html.twig', [
            'fielpromesse' => $fielpromesse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_gestion_fielpromesse_show', methods: ['GET'])]
    public function show(Fielpromesse $fielpromesse): Response
    {
        return $this->render('gestion/fielpromesse/show.html.twig', [
            'fielpromesse' => $fielpromesse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestion_fielpromesse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fielpromesse $fielpromesse, FielpromesseRepository $fielpromesseRepository, FormError $formError): Response
    {

        $form = $this->createForm(FielpromesseType::class, $fielpromesse, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_fielpromesse_edit', [
                    'id' =>  $fielpromesse->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_fielpromesse_index');


            if ($form->isValid()) {

                $fielpromesseRepository->save($fielpromesse, true);
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

        return $this->renderForm('gestion/fielpromesse/edit.html.twig', [
            'fielpromesse' => $fielpromesse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_gestion_fielpromesse_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Fielpromesse $fielpromesse, FielpromesseRepository $fielpromesseRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_gestion_fielpromesse_delete'
                ,   [
                        'id' => $fielpromesse->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $fielpromesseRepository->remove($fielpromesse, true);

            $redirect = $this->generateUrl('app_gestion_fielpromesse_index');

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

        return $this->renderForm('gestion/fielpromesse/delete.html.twig', [
            'fielpromesse' => $fielpromesse,
            'form' => $form,
        ]);
    }
}
