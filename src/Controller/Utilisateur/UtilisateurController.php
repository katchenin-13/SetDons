<?php

namespace App\Controller\Utilisateur;

use App\Repository\EmployeRepository;
use App\Service\FormError;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Form\UtilisateurEditType;
use App\Service\ActionRender;
use Doctrine\ORM\QueryBuilder;
use App\Repository\UtilisateurRepository;
use App\Service\Menu;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/utilisateur/utilisateur')]
class UtilisateurController extends AbstractController
{
    #[Route('/', name: 'app_utilisateur_utilisateur_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory,Menu $menu): Response
    {
        $permission = $menu->getPermission()["code"];
        $table = $dataTableFactory->create()
        ->add('username', TextColumn::class, ['label' => 'Pseudo'])
        ->add('email', TextColumn::class, ['label' => 'Email', 'field' => 'e.adresseMail'])
        ->add('nom', TextColumn::class, ['label' => 'Nom', 'field' => 'e.nom'])
        ->add('prenom', TextColumn::class, ['label' => 'Prénoms', 'field' => 'e.prenom'])
        ->add('fonction', TextColumn::class, ['label' => 'Fonction', 'field' => 'f.libelle'])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Utilisateur::class,
            'query' => function(QueryBuilder $qb){
                $qb->select('u, e, f')
                    ->from(Utilisateur::class, 'u')
                    ->join('u.employe', 'e')
                    ->join('e.fonction', 'f')
                ;
            }
        ])
        ->setName('dt_app_utilisateur_utilisateur');

        if($permission == "RS"){
            $renders = [
                'edit' =>  new ActionRender(function () {
                    return false;
                }),
                'delete' => new ActionRender(function () {
                    return false;
                }),
                'show' => new ActionRender(function () {
                    return true;
                }),
            ];

        }else if($permission == "CRUS"){
            $renders = [
                'edit' =>  new ActionRender(function () {
                    return true;
                }),
                'delete' => new ActionRender(function () {
                    return false;
                }),
                'show' => new ActionRender(function () {
                    return true;
                }),
            ];

        }else{
            $renders = [
                'edit' =>  new ActionRender(function () {
                    return true;
                }),
                'delete' => new ActionRender(function () {
                    return true;
                }),
                'show' => new ActionRender(function () {
                    return true;
                }),
            ];

        }

        
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
                , 'render' => function ($value, Utilisateur $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',
                            
                        'actions' => [
                            'edit' => [
                            'url' => $this->generateUrl('app_utilisateur_utilisateur_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_utilisateur_utilisateur_delete', ['id' => $value])
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


        return $this->render('utilisateur/utilisateur/index.html.twig', [
            'datatable' => $table,
            'permition' => $permission
        ]);
    }
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    #[Route('/new', name: 'app_utilisateur_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UtilisateurRepository $utilisateurRepository, FormError $formError): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_utilisateur_utilisateur_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_utilisateur_utilisateur_index');

           
//dd($form->getData()->getPassword());

            if ($form->isValid()) {

              $utilisateur->setPassword($this->hasher->hashPassword($utilisateur, $form->getData()->getPassword()));
                $utilisateurRepository->add($utilisateur, true);
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

        return $this->renderForm('utilisateur/utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_utilisateur_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur,EmployeRepository $employeRepository, UtilisateurRepository $utilisateurRepository, FormError $formError): Response
    {
        
        $form = $this->createForm(UtilisateurEditType::class, $utilisateur, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_utilisateur_utilisateur_edit', [
                    'id' =>  $utilisateur->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_utilisateur_utilisateur_index');

          // dd($utilisateur->getEmploye()->getId());
            if ($form->isValid()) {
                $utilisateur->setEmploye($employeRepository->find($utilisateur->getEmploye()->getId()));
                $utilisateur->setPassword($this->hasher->hashPassword($utilisateur, $form->getData()->getPassword()));
                $utilisateurRepository->add($utilisateur, true);
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

        return $this->renderForm('utilisateur/utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_utilisateur_utilisateur_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_utilisateur_utilisateur_delete'
                ,   [
                        'id' => $utilisateur->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $utilisateurRepository->remove($utilisateur, true);

            $redirect = $this->generateUrl('app_utilisateur_utilisateur_index');

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

        return $this->renderForm('utilisateur/utilisateur/delete.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }
}
