<?php

namespace App\Controller\Gestion;

use App\Entity\Promesse;
use App\Form\PromesseType;
use App\Repository\PromesseRepository;
use App\Service\ActionRender;
use App\Service\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
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

#[Route('/gestion/promesse')]
class PromesseController extends AbstractController
{

    /**
     * @Route("/acte/{id}/active", name="acte_promesse", methods={"GET"})
     * @param $id
     * @param Acte $parent
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function active(Promesse $parent, EntityManagerInterface $entityManager): Response
    {

        //  dd($parent);
        if ($parent->isStatusdon() == true) {

            $parent->setStatusdon(false);
        } else {

            $parent->setStatusdon(true);
        }
        // if ($parent->isMentions() == true) {

        //     $parent->setMentions(false);
        // } else {

        //     $parent->setMentions(true);
        // }

        $entityManager->persist($parent);
        $entityManager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'ça marche bien',
            'active' => $parent->isStatusdon(),
            // 'mention' =>$parent->isMentions(),
        ], 200);
    }

    #[Route('/pdf/generator', name: 'app_pdf_generator')]
    public function generatePdf(PromesseRepository $promesse): Response
    {
        $data = $promesse->findAll();
        $html =  $this->renderView('gestion/promesse/detail.html.twig', [
            'data' => $data
        ]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        return new Response(
            $dompdf->stream("resume", ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }

    #[Route('/', name: 'app_gestion_promesse_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
        ->add('dateremise', DateTimeColumn::class, [
                'label' => 'Date de réalisation',
                "format" => 'Y-m-d'
            ])
            ->add('nom', TextColumn::class, ['label' => 'Beneficiaire'])
            ->add('numero', TextColumn::class, ['label' => 'Tel du béneficiare'])
            ->add('communaute', TextColumn::class, ['label' => 'Communauté', 'field' => 'com.libelle'])
            ->add('Type', TextColumn::class, ['label' => 'Type'])
            ->createAdapter(ORMAdapter::class, [
            'entity' => Promesse::class,
            'query' => function (QueryBuilder $req) {
                $req->select('c,com')
                    ->from(Promesse::class, 'c')
                    ->join('c.communaute', 'com')
                   // ->join('c.fieldon', 'fl')
                    //   ->join('c.pointFocals','p')
                ;
            }
        ])
        ->setName('dt_app_gestion_promesse');

        $renders = [
            'statusdon_bloquer' =>  new ActionRender(function () {
                return true;
            }),
            'statusdon_debloquer' =>  new ActionRender(function () {
                return true;
            }),
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
                'label' => 'Actions', 'orderable' => false, 'globalSearchable' => false, 'className' => 'grid_row_actions', 'render' => function ($value, Promesse $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [
                            'statusdon_bloquer' => [
                                'url' => $this->generateUrl('acte_promesse', ['id' => $value]),
                                'ajax' => false,
                                'icon' => '%icon% fa  fa-unlock-alt',
                                'attrs' => ['class' => 'btn-main test'],
                                'render' => new ActionRender(function () use ($context) {
                                    return $context->isStatusdon();
                                })
                            ],
                            'statusdon_debloquer' => [
                                'url' => $this->generateUrl('acte_promesse', ['id' => $value]),
                                'ajax' => false,
                                'icon' => '%icon% fa fa-lock',
                                'attrs' => ['class' => 'btn-danger active test'],
                                'render' => new ActionRender(function () use ($context) {
                                    return !$context->isStatusdon();
                                })
                            ],
                            'show' => [
                                'url' => $this->generateUrl('app_gestion_promesse_show', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-eye', 'attrs' => ['class' => 'btn-success'], 'render' => $renders['edit']
                            ],

                            'edit' => [
                                'url' => $this->generateUrl('app_gestion_promesse_edit', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-pen', 'attrs' => ['class' => 'btn-default'], 'render' => $renders['edit']
                            ],
                            'delete' => [
                                'target' => '#exampleModalSizeNormal',
                                'url' => $this->generateUrl('app_gestion_promesse_delete', ['id' => $value]), 'ajax' => true, 'icon' => '%icon% bi bi-trash', 'attrs' => ['class' => 'btn-danger'],  'render' => $renders['delete']
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


        return $this->render('gestion/promesse/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_gestion_promesse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PromesseRepository $promesseRepository, FormError $formError): Response
    {
        $promesse = new Promesse();
        $form = $this->createForm(PromesseType::class, $promesse, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_promesse_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_promesse_index');




            if ($form->isValid()) {

                $promesseRepository->save($promesse, true);
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

        return $this->renderForm('gestion/promesse/new.html.twig', [
            'promesse' => $promesse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_gestion_promesse_show', methods: ['GET'])]
    public function show(Promesse $promesse): Response
    {
        return $this->render('gestion/promesse/show.html.twig', [
            'promesse' => $promesse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestion_promesse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Promesse $promesse, PromesseRepository $promesseRepository, FormError $formError): Response
    {

        $form = $this->createForm(PromesseType::class, $promesse, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_promesse_edit', [
                    'id' =>  $promesse->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_promesse_index');


            if ($form->isValid()) {

                $promesseRepository->save($promesse, true);
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

        return $this->renderForm('gestion/promesse/edit.html.twig', [
            'promesse' => $promesse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_gestion_promesse_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Promesse $promesse, PromesseRepository $promesseRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_gestion_promesse_delete'
                ,   [
                        'id' => $promesse->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $promesseRepository->remove($promesse, true);

            $redirect = $this->generateUrl('app_gestion_promesse_index');

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

        return $this->renderForm('gestion/promesse/delete.html.twig', [
            'promesse' => $promesse,
            'form' => $form,
        ]);
    }
}
