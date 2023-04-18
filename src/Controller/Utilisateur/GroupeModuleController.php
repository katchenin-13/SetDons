<?php

namespace App\Controller\Utilisateur;

use App\Entity\GroupeModule;
use App\Form\GroupeModuleType;
use App\Repository\GroupeModuleRepository;
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

#[Route('/utilisateur/groupe/module')]
class GroupeModuleController extends AbstractController
{
    #[Route('/', name: 'app_utilisateur_groupe_module_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
            ->add('titre', TextColumn::class, ['label' => 'Libellé'])
            ->add('ordre', TextColumn::class, ['label' => 'Ordre'])
            ->createAdapter(ORMAdapter::class, [
            'entity' => GroupeModule::class,
        ])
        ->setName('dt_app_utilisateur_groupe_module');

        $renders = [
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
                , 'render' => function ($value, GroupeModule $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [
                            'edit' => [
                            'url' => $this->generateUrl('app_utilisateur_groupe_module_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_utilisateur_groupe_module_delete', ['id' => $value])
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


        return $this->render('utilisateur/groupe_module/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_groupe_module_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GroupeModuleRepository $groupeModuleRepository, FormError $formError): Response
    {
        $groupeModule = new GroupeModule();
        $form = $this->createForm(GroupeModuleType::class, $groupeModule, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_utilisateur_groupe_module_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_utilisateur_groupe_module_index');




            if ($form->isValid()) {

                $groupeModuleRepository->save($groupeModule, true);
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

        return $this->renderForm('utilisateur/groupe_module/new.html.twig', [
            'groupe_module' => $groupeModule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_utilisateur_groupe_module_show', methods: ['GET'])]
    public function show(GroupeModule $groupeModule): Response
    {
        return $this->render('utilisateur/groupe_module/show.html.twig', [
            'groupe_module' => $groupeModule,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_groupe_module_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GroupeModule $groupeModule, GroupeModuleRepository $groupeModuleRepository, FormError $formError): Response
    {

        $form = $this->createForm(GroupeModuleType::class, $groupeModule, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_utilisateur_groupe_module_edit', [
                    'id' =>  $groupeModule->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_utilisateur_groupe_module_index');


            if ($form->isValid()) {

                $groupeModuleRepository->save($groupeModule, true);
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

        return $this->renderForm('utilisateur/groupe_module/edit.html.twig', [
            'groupe_module' => $groupeModule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_utilisateur_groupe_module_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, GroupeModule $groupeModule, GroupeModuleRepository $groupeModuleRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_utilisateur_groupe_module_delete'
                ,   [
                        'id' => $groupeModule->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $groupeModuleRepository->remove($groupeModule, true);

            $redirect = $this->generateUrl('app_utilisateur_groupe_module_index');

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

        return $this->renderForm('utilisateur/groupe_module/delete.html.twig', [
            'groupe_module' => $groupeModule,
            'form' => $form,
        ]);
    }
}
