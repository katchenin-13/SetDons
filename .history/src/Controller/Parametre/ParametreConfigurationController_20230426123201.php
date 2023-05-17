<?php

namespace App\Controller\Parametre;

use App\Controller\FileTrait;
use App\Entity\Demande;
use App\Entity\ParametreConfiguration;
use App\Form\ParametreConfigurationType;
use App\Repository\ParametreConfigurationRepository;
use App\Service\ActionRender;
use App\Service\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
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
use Symfony\Component\Workflow\Registry;

#[Route('/parametre/parametre/configuration')]
class ParametreConfigurationController extends AbstractController
{
    use FileTrait;

    private const UPLOAD_PATH = 'parametre';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;

    }

    #[Route('/', name: 'app_parametre_parametre_configuration_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
            ->add('denomination', TextColumn::class, ['label' => 'Entreprise', 'field' => 'e.denomination'])
            ->add('primaryColor', TextColumn::class, ['label' => 'Couleur primaire'])
            ->add('secondaryColor', TextColumn::class, ['label' => 'Couleur secondaire'])
        ->createAdapter(ORMAdapter::class, [
            'entity' => ParametreConfiguration::class,
            'query' => function(QueryBuilder $qb)  {
                $qb->select('d, e')
                    ->from(ParametreConfiguration::class, 'd')
                    ->join('d.entreprise', 'e')
                    ->andWhere('d.entreprise =:entreprise')
                    ->setParameter('entreprise',$this->security->getUser()->getEmploye()->getEntreprise());

            }
        ])
        ->setName('dt_app_parametre_parametre_configuration');

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
                , 'render' => function ($value, ParametreConfiguration $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [
                            'edit' => [
                            'url' => $this->generateUrl('app_parametre_parametre_configuration_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_parametre_parametre_configuration_delete', ['id' => $value])
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


        return $this->render('parametre/parametre_configuration/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_parametre_parametre_configuration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ParametreConfigurationRepository $parametreConfigurationRepository, FormError $formError): Response
    {
        $parametreConfiguration = new ParametreConfiguration();
        $form = $this->createForm(ParametreConfigurationType::class, $parametreConfiguration, [
            'method' => 'POST',
            'doc_options' => [
                'uploadDir' => $this->getUploadDir(self::UPLOAD_PATH, true),
                'attrs' => ['class' => 'filestyle'],
            ],
            'logo' => [
                'doc_options' => [
                    'uploadDir' => $this->getUploadDir(self::UPLOAD_PATH, true),
                    'attrs' => ['class' => 'filestyle'],
                ],
            ],
            'action' => $this->generateUrl('app_parametre_parametre_configuration_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_parametre_parametre_configuration_index');




            if ($form->isValid()) {

                $parametreConfigurationRepository->save($parametreConfiguration, true);
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

        return $this->renderForm('parametre/parametre_configuration/new.html.twig', [
            'parametre_configuration' => $parametreConfiguration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_parametre_parametre_configuration_show', methods: ['GET'])]
    public function show(ParametreConfiguration $parametreConfiguration): Response
    {
        return $this->render('parametre/parametre_configuration/show.html.twig', [
            'parametre_configuration' => $parametreConfiguration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_parametre_parametre_configuration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParametreConfiguration $parametreConfiguration, ParametreConfigurationRepository $parametreConfigurationRepository, FormError $formError): Response
    {

        $form = $this->createForm(ParametreConfigurationType::class, $parametreConfiguration, [
            'method' => 'POST',
           'doc_options' => [
                'uploadDir' => $this->getUploadDir(self::UPLOAD_PATH, true),
                'attrs' => ['class' => 'filestyle'],
            ],
            'logo' => [
                'doc_options' => [
                    'uploadDir' => $this->getUploadDir(self::UPLOAD_PATH, true),
                    'attrs' => ['class' => 'filestyle'],
                ],
            ],
            'action' => $this->generateUrl('app_parametre_parametre_configuration_edit', [
                    'id' =>  $parametreConfiguration->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_parametre_parametre_configuration_index');


            if ($form->isValid()) {

                $parametreConfigurationRepository->save($parametreConfiguration, true);
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

        return $this->renderForm('parametre/parametre_configuration/edit.html.twig', [
            'parametre_configuration' => $parametreConfiguration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_parametre_parametre_configuration_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, ParametreConfiguration $parametreConfiguration, ParametreConfigurationRepository $parametreConfigurationRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_parametre_parametre_configuration_delete'
                ,   [
                        'id' => $parametreConfiguration->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $parametreConfigurationRepository->remove($parametreConfiguration, true);

            $redirect = $this->generateUrl('app_parametre_parametre_configuration_index');

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

        return $this->renderForm('parametre/parametre_configuration/delete.html.twig', [
            'parametre_configuration' => $parametreConfiguration,
            'form' => $form,
        ]);
    }
}
