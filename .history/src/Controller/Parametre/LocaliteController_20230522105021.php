<?php

namespace App\Controller\Parametre;

use App\Entity\Localite;
use App\Form\LocaliteType;
use App\Repository\LocaliteRepository;
use App\Service\ActionRender;
use App\Service\FormError;
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

#[Route('/parametre/localite')]
class LocaliteController extends AbstractController
{
   

    // /**
    //  * cette fonction permet de generer un pdf de localite
    //  *
    //  * @param LocaliteRepository $localite
    //  * @return Response
    //  */
    // #[Route('/pdf/generator', name: 'app_pdf_generator')]
    // public function generatePdf(LocaliteRepository $localite): Response
    // {
    //     $data = $localite->findAll();
    //     $html =  $this->renderView('gestion/localite/detail.html.twig', [
    //         'data' => $data
    //     ]);
    //     $dompdf = new Dompdf();
    //     $dompdf->loadHtml($html);
    //     $dompdf->render();
    //     return new Response(
    //         $dompdf->stream("resume", ["Attachment" => false]),
    //         Response::HTTP_OK,
    //         ['Content-Type' => 'application/pdf']
    //     );
    // }

    #[Route('/', name: 'app_parametre_localite_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
        ->add('code', TextColumn::class, ['label' => 'Code'])
        ->add('libelle', TextColumn::class, ['label' => 'Libellé'])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Localite::class,
        ])
        ->setName('dt_app_parametre_localite');

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

            // if($permission == "RS"){
        //     $renders = [
        //         'edit' =>  new ActionRender(function () {
        //             return false;
        //         }),
        //         'delete' => new ActionRender(function () {
        //             return false;
        //         }),
        //         'show' => new ActionRender(function () {
        //             return true;
        //         }),
        //     ];

        // }else if($permission == "CRUS"){
        //     $renders = [
        //         'edit' =>  new ActionRender(function () {
        //             return true;
        //         }),
        //         'delete' => new ActionRender(function () {
        //             return false;
        //         }),
        //         'show' => new ActionRender(function () {
        //             return true;
        //         }),
        //     ];

        // }else{
        //     $renders = [
        //         'edit' =>  new ActionRender(function () {
        //             return true;
        //         }),
        //         'delete' => new ActionRender(function () {
        //             return true;
        //         }),
        //         'show' => new ActionRender(function () {
        //             return true;
        //         }),
        //     ];

        // }


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
                , 'render' => function ($value, Localite $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [
                             'show' => [
                            'url' => $this->generateUrl('app_parametre_localite_show', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-eye'
                            , 'attrs' => ['class' => 'btn-success']
                            , 'render' => $renders['show']
                        ],  
                            'edit' => [
                            'url' => $this->generateUrl('app_parametre_localite_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_parametre_localite_delete', ['id' => $value])
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


        return $this->render('parametre/localite/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_parametre_localite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LocaliteRepository $localiteRepository, FormError $formError): Response
    {
        $localite = new Localite();
        $form = $this->createForm(LocaliteType::class, $localite, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_parametre_localite_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_parametre_localite_index');




            if ($form->isValid()) {

                $localiteRepository->save($localite, true);
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

        return $this->renderForm('parametre/localite/new.html.twig', [
            'localite' => $localite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_parametre_localite_show', methods: ['GET'])]
    public function show(Localite $localite): Response
    {
        return $this->render('parametre/localite/show.html.twig', [
            'localite' => $localite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_parametre_localite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Localite $localite, LocaliteRepository $localiteRepository, FormError $formError): Response
    {

        $form = $this->createForm(LocaliteType::class, $localite, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_parametre_localite_edit', [
                    'id' =>  $localite->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_parametre_localite_index');


            if ($form->isValid()) {

                $localiteRepository->save($localite, true);
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

        return $this->renderForm('parametre/localite/edit.html.twig', [
            'localite' => $localite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_parametre_localite_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Localite $localite, LocaliteRepository $localiteRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_parametre_localite_delete'
                ,   [
                        'id' => $localite->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $localiteRepository->remove($localite, true);

            $redirect = $this->generateUrl('app_parametre_localite_index');

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

        return $this->renderForm('parametre/localite/delete.html.twig', [
            'localite' => $localite,
            'form' => $form,
        ]);
    }
}
