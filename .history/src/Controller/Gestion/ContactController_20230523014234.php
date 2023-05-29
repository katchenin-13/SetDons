<?php

namespace App\Controller\Gestion;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\FormError;
use App\Service\ActionRender;
use Doctrine\ORM\QueryBuilder;
use App\Repository\ContactRepository;
use App\Service\Menu;
use Dompdf\Dompdf;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/gestion/contact')]
class ContactController extends AbstractController
{
    /**
     * Cette fonction permet de generer un pdf(reporting de sur contact)
     *
     * @param ContactRepository $contact
     * @return Response
     */
    #[Route('/pdf/generator', name: 'app_pdf_generator')]
    public function generatePdf(ContactRepository $contact): Response
    {
        $data = $contact->findAll();
        $html =  $this->renderView('gestion/contact/detail.html.twig', [
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


    #[Route('/', name: 'app_gestion_contact_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory,Menu $menu): Response
    {
        $permission = $menu->getPermission()["code"];
        $table = $dataTableFactory->create()
        ->add('nom', TextColumn::class, ['label' => 'Nom et Prenom'])
        ->add('fonction', TextColumn::class, ['label' => 'Fontion'])
        ->add('numero', TextColumn::class, ['label' => 'Numéro'])
        // ->add('email', TextColumn::class, ['label' => 'Email'])
        ->add('communaute', TextColumn::class, ['label' => 'Communauté','field' => 'co.libelle'])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Contact::class,
            'query'=> function(QueryBuilder $req){
              $req->select('c,co')
                  ->from(Contact::class,'c')
                  ->join('c.communaute', 'co')
                ;
            }
        ])
        ->setName('dt_app_gestion_contact');

        if($permission == "RS"){
            $renders = [
                
                'edit' =>  new ActionRender(function () {
                    return false;
                }),
                'delete' => new ActionRender(function () {
                    return false;
                }),
                // 'show' => new ActionRender(function () {
                //     return true;
                // }),
            ];

        }else if($permission == "CRUS"){
            $renders = [
                'edit' =>  new ActionRender(function () {
                    return true;
                }),
                'delete' => new ActionRender(function () {
                    return false;
                }),
                // 'show' => new ActionRender(function () {
                //     return true;
                // }),
            ];

        }else{
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
                // 'show' => new ActionRender(function () {
                //     return true;
                // }),
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
                , 'render' => function ($value, Contact $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',

                        'actions' => [

                            'show' => [
                            'url' => $this->generateUrl('app_gestion_contact_show', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-eye'
                            , 'attrs' => ['class' => 'btn-success']
                            , 'render' => $renders['show']
                        ],  
                            'edit' => [
                            'url' => $this->generateUrl('app_gestion_contact_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_gestion_contact_delete', ['id' => $value])
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


        return $this->render('gestion/contact/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_gestion_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $contactRepository, FormError $formError): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_contact_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_contact_index');




            if ($form->isValid()) {

                $contactRepository->save($contact, true);
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

        return $this->renderForm('gestion/contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_gestion_contact_show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        return $this->render('gestion/contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestion_contact_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contact $contact, ContactRepository $contactRepository, FormError $formError): Response
    {

        $form = $this->createForm(ContactType::class, $contact, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_gestion_contact_edit', [
                    'id' =>  $contact->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_gestion_contact_index');


            if ($form->isValid()) {

                $contactRepository->save($contact, true);
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

        return $this->renderForm('gestion/contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_gestion_contact_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Contact $contact, ContactRepository $contactRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_gestion_contact_delete'
                ,   [
                        'id' => $contact->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $contactRepository->remove($contact, true);

            $redirect = $this->generateUrl('app_gestion_contact_index');

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

        return $this->renderForm('gestion/contact/delete.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }
}
