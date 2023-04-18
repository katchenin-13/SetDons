<?php

namespace App\Controller\Gestion;

use Mpdf\Tag\Dd;
use App\Entity\Don;
use App\Form\DonType;
use App\Service\FormError;
use App\Entity\Beneficiaire;
use App\Repository\BeneficiaireRepository;
use App\Service\ActionRender;
use Doctrine\ORM\QueryBuilder;
use App\Repository\DonRepository;
use App\Repository\CommunauteRepository;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\NumberColumn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gestion/don')]
class DonController extends AbstractController
{
    #[Route('/', name: 'app_gestion_don_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory ): Response
    {
       
        $table = $dataTableFactory->create()

            // ->add('Beneficiaire', TextColumn::class, ['label' => 'Motif','field' => 'b.nom'])
            // ->add('numero', TextColumn::class, ['label' => 'Tel du béneficiare','field'=>'b.numero'])
            // ->add('communaute', TextColumn::class, ['label' => 'Communauté','field' => 'b.communaute'])
            ->add('dateremise', DateTimeColumn::class, [
                'label' => 'Date de remise',
                "format" => 'Y-m-d'
            ])
     
        ->add('remispar', TextColumn::class, ['label' => 'Remise par'])
        // ->add('typedon', TextColumn::class, ['label' => 'Type','field'=>'f.typedon'])
        //->add('montantdon', NumberColumn::class, [
        //     'label' => 'Montant / Valeur Estimative',
        //     'field'=>'f.montantdon',
        //     "format" => 'Y-m-d'
        //     ])

        ->createAdapter(ORMAdapter::class, [
            'entity' => Don::class,
            'query'=> function(QueryBuilder $req){
              $req->select('d')
                  ->from(Don::class,'d')
                //   ->join('d.Beneficiaire','b')
                //  ->join('d.fieldon','f')
                  
                ;
            }
        ])
       
        ->setName('dt_app_gestion_don');
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
                , 'render' => function ($value, Don $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [
                            'edit' => [
                            'url' => $this->generateUrl('app_gestion_don_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_gestion_don_delete', ['id' => $value])
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


        return $this->render('gestion/don/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_gestion_don_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DonRepository $donRepository,CommunauteRepository $communauteRepository, FormError $formError): Response
    {
        $don = new Don();

        $comm = $communauteRepository->findAll();
        $beneficiaire = new Beneficiaire();
        foreach($comm  as $elem){
            $beneficiaire->setCommunaute($elem);
        }
       
        $beneficiaire->setNom("");
        $beneficiaire->setNumero("");
        $beneficiaire->setEmail("");
        $form1 = $this->createForm(DonType::class, $don, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_don_new')
        ]);

        $don->addBeneficiaire($beneficiaire);

        $form = $this->createForm(DonType::class, $don, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_don_new')
        ]);

        // $options = $form->get('promesse')->getConfig()->getOptions();
        // $choices = $options['don_promesse']->getChoices();

    //    dd($form);
    
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_don_index');




            if ($form->isValid()) {

                $donRepository->save($don, true);
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

        return $this->renderForm('gestion/don/new.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_gestion_don_show', methods: ['GET'])]
    public function show(Don $don): Response
    {
        return $this->render('gestion/don/show.html.twig', [
            'don' => $don,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestion_don_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Don $don, DonRepository $donRepository, FormError $formError): Response
    {

        $form = $this->createForm(DonType::class, $don, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_don_edit', [
                    'id' =>  $don->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_don_index');


            if ($form->isValid()) {

                $donRepository->save($don, true);
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

        return $this->renderForm('gestion/don/edit.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_gestion_don_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Don $don, DonRepository $donRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_gestion_don_delete'
                ,   [
                        'id' => $don->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $donRepository->remove($don, true);

            $redirect = $this->generateUrl('app_gestion_don_index');

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

        return $this->renderForm('gestion/don/delete.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }
}
