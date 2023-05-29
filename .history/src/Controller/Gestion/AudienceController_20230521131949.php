<?php

namespace App\Controller\Gestion;

use App\Entity\Audience;
use App\Form\AudienceType;
use App\Service\FormError;
use App\Service\ActionRender;
use Doctrine\ORM\QueryBuilder;
use App\Repository\AudienceRepository;
use App\Repository\ModuleGroupePermitionRepository;
use App\Service\Menu;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

#[Route('/gestion/audience')]
class AudienceController extends AbstractController
{

    /**
     * @Route("/acte/{id}/active", name="acte_audience", methods={"GET"})
     * @param $id
     * @param Acte $parent
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function active(Audience $parent, EntityManagerInterface $entityManager): Response
    {

        //  dd($parent);
        if ($parent->isStatusaudience() == true) {

             $parent->setStatusaudience(false);

        } else {

            $parent->setStatusaudience(true);


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
            'active' => $parent->isStatusaudience(),
            // 'mention' =>$parent->isMentions(),
       ],200);


    }

   
     
    #[Route('/pdf/', name: 'app_pdt_generator')]
    public function generatePdfPersonne( AudienceRepository $audience, PdfService $pdf)
    {
        $audiences = $audience->findAll();
        //dd($audiences);
        $html = $this->render('pdf_generator/pdt.html.twig', ['audiences'=>$audiences]);
        $pdf->showPdfFile($html);

        // $html = $this->render('personne/detail.html.twig', ['personne' => $personne]);
        // $pdf->showPdfFile($html);
    }

    #[Route('/', name: 'app_gestion_audience_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory,Menu $menu,ModuleGroupePermitionRepository $per): Response
    {

      //dd($aud);


        // $permission = $per->getPermission(1,"app_config_audience_index");
        // dd($permission);
       // $menu->getPermission()["code"];
    //    $audiences = $audience->SelectInterval('2023-04-1','2023-04-20',1);
    //    dd($audiences);

        $table = $dataTableFactory->create()
        ->add('daterencontre', DateTimeColumn::class, [
            'label' => 'Date de la rencontre',
              "format" => 'Y-m-d'
         ])
        ->add('communaute', TextColumn::class, ['label' => 'Communauté','field' => 'co.libelle'])
        ->add('nomchef', TextColumn::class, ['label' => 'Nom du chef'])
        ->add('numero', TextColumn::class, ['label' => 'Numéro'])
        ->add('motif', TextColumn::class, ['label' => 'Motif'])
        // ->add('email', TextColumn::class, ['label' => 'Email'])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Audience::class,
            'query'=> function(QueryBuilder $req){
              $req->select('a,co')
                  ->from(Audience::class,'a')
                  ->join('a.communaute', 'co')
                ;
            }
        ])
        ->setName('dt_app_gestion_audience');

        $renders = [
            // 'mention_bloquer' =>  new ActionRender(function () {
            //     return true;
            // }),
            // 'mention_debloquer' =>  new ActionRender(function () {
            //     return true;
            // }),
            'statusaudience_bloquer' =>  new ActionRender(function () {
                return true;
            }),
            'statusaudience_debloquer' =>  new ActionRender(function () {
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

        //   if($permission == "RS"){
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
                , 'render' => function ($value, Audience $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [
                            // 'mention_bloquer' => [
                            //     'url' => $this->generateUrl('acte_audience', ['id' => $value]),
                            //      'ajax' => false, 'icon' => '%icon% fa fa-unlock', 
                            //      'attrs' => ['class' => 'btn-danger test'],
                            //      'render' => new ActionRender(function () use ($context) {
                            //         return $context->isMentions();
                            //     })
                            // ],
                            // 'mention_debloquer' => [
                            //     'url' => $this->generateUrl('acte_audience', ['id' => $value]), 
                            //     'ajax' => false,
                            //      'icon' => '%icon% fa fa-unlock-alt',
                            //      'attrs' => ['class' => 'btn-main1 active test   '],
                            //       'render' => new ActionRender(function () use ($context) {
                            //         return !$context->isMentions();
                            //     })
                            // ],
                            'statusaudience_bloquer' => [
                                 'url' => $this->generateUrl('acte_audience',['id' => $value])
                                 ,'ajax' => false
                                 ,'icon' => '%icon% fa  fa-unlock-alt'
                                ,'attrs' => ['class' => 'btn-main test']
                                , 'render' => new ActionRender(function () use ($context) {
                                    return $context->isStatusaudience();
                                })
                            ],
                            'statusaudience_debloquer' => [
                                 'url' => $this->generateUrl('acte_audience',['id' => $value])
                                 ,'ajax' => false
                                , 'icon' => '%icon% fa fa-lock'
                                , 'attrs' => ['class' => 'btn-danger active test']
                                , 'render' => new ActionRender(function () use ($context) {
                                    return !$context->isStatusaudience();
                                })
                            ],
                            'show' => [
                                'url' => $this->generateUrl('app_gestion_audience_show', ['id' => $value])
                                , 'ajax' => true
                                , 'icon' => '%icon% bi bi-eye'
                                , 'attrs' => ['class' => 'btn-success']
                                , 'render' => $renders['edit']
                            ],
                            'edit' => [
                            'url' => $this->generateUrl('app_gestion_audience_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                           , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_gestion_audience_delete', ['id' => $value])
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


        return $this->render('gestion/audience/index.html.twig', [
             'datatable' => $table,
             ''
        //     'permition' => $permission
         ]);
    }

    #[Route('/new', name: 'app_gestion_audience_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AudienceRepository $audienceRepository, FormError $formError): Response
    {  
        //$rep = $audienceRepository->findBy(array("id"=>1));
    //     dd(count($rep));
        $audience = new Audience();
        $form = $this->createForm(AudienceType::class, $audience, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_audience_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_audience_index');




            if ($form->isValid()) {

                $audienceRepository->save($audience, true);
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

        return $this->renderForm('gestion/audience/new.html.twig', [
            'audience' => $audience,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_gestion_audience_show', methods: ['GET'])]
    public function show(Audience $audience): Response
    {
        return $this->render('gestion/audience/show.html.twig', [
            'audience' => $audience,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestion_audience_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Audience $audience, AudienceRepository $audienceRepository, FormError $formError): Response
    {

        $form = $this->createForm(AudienceType::class, $audience, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_audience_edit', [
                    'id' =>  $audience->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_audience_index');


            if ($form->isValid()) {

                $audienceRepository->save($audience, true);
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

        return $this->renderForm('gestion/audience/edit.html.twig', [
            'audience' => $audience,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_gestion_audience_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Audience $audience, AudienceRepository $audienceRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_gestion_audience_delete'
                ,   [
                        'id' => $audience->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $audienceRepository->remove($audience, true);

            $redirect = $this->generateUrl('app_gestion_audience_index');

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

        return $this->renderForm('gestion/audience/delete.html.twig', [
            'audience' => $audience,
            'form' => $form,
        ]);
    }
}
