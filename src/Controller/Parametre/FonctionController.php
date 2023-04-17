<?php

namespace App\Controller\Parametre;

use App\Classes\UploadFile;
use App\Entity\Fonction;
use App\Form\FonctionType;
use App\Form\UploadFileType;
use App\Repository\FonctionRepository;
use App\Service\ActionRender;
use App\Service\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/parametre/fonction')]
class FonctionController extends AbstractController
{
    #[Route('/', name: 'app_parametre_fonction_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
        ->add('code', TextColumn::class, ['label' => 'Code'])
        ->add('libelle', TextColumn::class, ['label' => 'Libellé'])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Fonction::class,
        ])
        ->setName('dt_app_parametre_fonction');

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
                , 'render' => function ($value, Fonction $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',
                            
                        'actions' => [
                            'edit' => [
                            'url' => $this->generateUrl('app_parametre_fonction_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_parametre_fonction_delete', ['id' => $value])
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


        return $this->render('parametre/fonction/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_parametre_fonction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FonctionRepository $fonctionRepository, FormError $formError): Response
    {
        $fonction = new Fonction();
        $form = $this->createForm(FonctionType::class, $fonction, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_parametre_fonction_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_parametre_fonction_index');

           


            if ($form->isValid()) {
                
                $fonctionRepository->add($fonction, true);
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

        return $this->renderForm('parametre/fonction/new.html.twig', [
            'fonction' => $fonction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_parametre_fonction_show', methods: ['GET'])]
    public function show(Fonction $fonction): Response
    {
        return $this->render('parametre/fonction/show.html.twig', [
            'fonction' => $fonction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_parametre_fonction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fonction $fonction, FonctionRepository $fonctionRepository, FormError $formError): Response
    {
        
        $form = $this->createForm(FonctionType::class, $fonction, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_parametre_fonction_edit', [
                    'id' =>  $fonction->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_parametre_fonction_index');

           
            if ($form->isValid()) {
                
                $fonctionRepository->add($fonction, true);
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

        return $this->renderForm('parametre/fonction/edit.html.twig', [
            'fonction' => $fonction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_parametre_fonction_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Fonction $fonction, FonctionRepository $fonctionRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_parametre_fonction_delete'
                ,   [
                        'id' => $fonction->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $fonctionRepository->remove($fonction, true);

            $redirect = $this->generateUrl('app_parametre_fonction_index');

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

        return $this->renderForm('parametre/fonction/delete.html.twig', [
            'fonction' => $fonction,
            'form' => $form,
        ]);
    }


    #[Route('/fonction/addFile', name: 'fonction_addFile_new', methods: ['GET', 'POST'])]
    public function addFile(Request $request,FormError $formError,FonctionRepository $fonctionRepository, EntityManagerInterface $entityManager)
    {
        $dossier = new UploadFile();
        $form = $this->createForm(UploadFileType::class,$dossier, [
            'method' => 'POST',
            'action' => $this->generateUrl('fonction_addFile_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {

            $response = [];
            $redirect = $this->generateUrl('app_parametre_fonction_index');

            //

            if ($form->isValid()) {

                $file = $form->get("upload_file")->getData(); // get the file from the sent request


                $fileFolder = $this->getParameter('kernel.project_dir') . '/public/uploads/';  //choose the folder in which the uploaded file will be stored

                //dd($fileFolder);
                $filePathName = md5(uniqid()) . $file->getClientOriginalName();

                try {
                    $file->move($fileFolder, $filePathName);
                } catch (FileException $e) {
                    dd($e);
                }

                $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file

                $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the first file line
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array


                foreach ($sheetData as $Row)
                {

                    $ref = $Row['A'];     // store the first_name on each iteration
                    $desig = $Row['B'];   // store the last_name on each iteration


                    $fonction_existant = $fonctionRepository->findOneBy(array('code' => $ref));


                    // make sure that the user does not already exists in your db
                    if (!$fonction_existant)
                    {

                        $fonction = new Fonction();
                        $fonction->setCode($ref);
                        $fonction->setLibelle($desig);
                        $entityManager->persist($fonction);
                        $entityManager->flush();
                        // here Doctrine checks all the fields of all fetched data and make a transaction to the database.
                    }else{

                        $fonction_existant->setCode($ref);
                        $fonction_existant->setLibelle($desig);
                        $entityManager->persist($fonction_existant);
                        $entityManager->flush();
                    }
                }

                $data = true;
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);


            }


            if ($isAjax) {
                return $this->json( compact('statut', 'message', 'redirect', 'data'), $statutCode);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect, Response::HTTP_OK);
                }
            }

        }
        return $this->renderForm('parametre/uploadFile/upload_file_new.html.twig', [
            'form' => $form,
        ]);
    }
}
