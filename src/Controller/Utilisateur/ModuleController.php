<?php

namespace App\Controller\Utilisateur;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
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

#[Route('/utilisateur/module')]
class ModuleController extends AbstractController
{
    #[Route('/', name: 'app_utilisateur_module_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
            ->add('titre', TextColumn::class, ['label' => 'Libellé'])
            ->add('ordre', TextColumn::class, ['label' => 'Ordre'])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Module::class,
        ])
        ->setName('dt_app_utilisateur_module');

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
                , 'render' => function ($value, Module $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [
                            'edit' => [
                            'url' => $this->generateUrl('app_utilisateur_module_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_utilisateur_module_delete', ['id' => $value])
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


        return $this->render('utilisateur/module/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_module_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ModuleRepository $moduleRepository, FormError $formError): Response
    {
        $module = new Module();
        $form = $this->createForm(ModuleType::class, $module, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_utilisateur_module_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_utilisateur_module_index');




            if ($form->isValid()) {

                $moduleRepository->save($module, true);
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

        return $this->renderForm('utilisateur/module/new.html.twig', [
            'module' => $module,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_utilisateur_module_show', methods: ['GET'])]
    public function show(Module $module): Response
    {
        return $this->render('utilisateur/module/show.html.twig', [
            'module' => $module,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_module_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Module $module, ModuleRepository $moduleRepository, FormError $formError): Response
    {

        $form = $this->createForm(ModuleType::class, $module, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_utilisateur_module_edit', [
                    'id' =>  $module->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_utilisateur_module_index');


            if ($form->isValid()) {

                $moduleRepository->save($module, true);
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

        return $this->renderForm('utilisateur/module/edit.html.twig', [
            'module' => $module,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_utilisateur_module_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Module $module, ModuleRepository $moduleRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_utilisateur_module_delete'
                ,   [
                        'id' => $module->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $moduleRepository->remove($module, true);

            $redirect = $this->generateUrl('app_utilisateur_module_index');

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

        return $this->renderForm('utilisateur/module/delete.html.twig', [
            'module' => $module,
            'form' => $form,
        ]);
    }
}
