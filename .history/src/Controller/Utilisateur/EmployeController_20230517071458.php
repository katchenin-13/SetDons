<?php

namespace App\Controller\Utilisateur;

use App\Classes\UploadFile;
use App\Entity\Employe;
use App\Form\EmployeType;
use App\Form\UploadFileType;
use App\Repository\CiviliteRepository;
use App\Repository\EmployeRepository;
use App\Repository\FonctionRepository;
use App\Service\FormError;
use App\Service\ActionRender;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\DataTableFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/utilisateur/employe')]
class EmployeController extends AbstractController
{
    #[Route('/', name: 'app_utilisateur_employe_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
        ->add('matricule', TextColumn::class, ['label' => 'Matricule'])
        ->add('civilite', TextColumn::class, ['field' => 'civilite.code', 'label' => 'Civilité'])
        ->add('nom', TextColumn::class, ['label' => 'Nom'])
        ->add('prenom', TextColumn::class, ['label' => 'Prénoms'])
        ->add('adresseMail', TextColumn::class, ['label' => 'Email'])
        ->add('fonction', TextColumn::class, ['field' => 'fonction.libelle', 'label' => 'Rol'])
        ->createAdapter(ORMAdapter::class, [
            'entity' => Employe::class,
            'query' => function(QueryBuilder $qb){
                $qb->select('e, civilite, fonction')
                    ->from(Employe::class, 'e')
                    ->join('e.civilite', 'civilite')
                    ->join('e.fonction', 'fonction')
                ;
            }
        ])
        ->setName('dt_app_utilisateur_employe');

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
                , 'render' => function ($value, Employe $context) use ($renders) {
                    $options = [
                        'default_class' => 'btn btn-xs btn-clean btn-icon mr-2 ',
                        'target' => '#exampleModalSizeLg2',
                            
                        'actions' => [
                            'edit' => [
                            'url' => $this->generateUrl('app_utilisateur_employe_edit', ['id' => $value])
                            , 'ajax' => true
                            , 'icon' => '%icon% bi bi-pen'
                            , 'attrs' => ['class' => 'btn-default']
                            , 'render' => $renders['edit']
                        ],
                        'delete' => [
                            'target' => '#exampleModalSizeNormal',
                            'url' => $this->generateUrl('app_utilisateur_employe_delete', ['id' => $value])
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


        return $this->render('utilisateur/employe/index.html.twig', [
            'datatable' => $table
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_employe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmployeRepository $employeRepository, FormError $formError): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_utilisateur_employe_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_utilisateur_employe_index');

           


            if ($form->isValid()) {
                
                $employeRepository->add($employe, true);
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

        return $this->renderForm('utilisateur/employe/new.html.twig', [
            'employe' => $employe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_utilisateur_employe_show', methods: ['GET'])]
    public function show(Employe $employe): Response
    {
        return $this->render('utilisateur/employe/show.html.twig', [
            'employe' => $employe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_employe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employe $employe, EmployeRepository $employeRepository, FormError $formError): Response
    {
        
        $form = $this->createForm(EmployeType::class, $employe, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_utilisateur_employe_edit', [
                    'id' =>  $employe->getId()
            ])
        ]);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = [];
            $redirect = $this->generateUrl('app_utilisateur_employe_index');

           
            if ($form->isValid()) {
                
                $employeRepository->add($employe, true);
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

        return $this->renderForm('utilisateur/employe/edit.html.twig', [
            'employe' => $employe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_utilisateur_employe_delete', methods: ['DELETE', 'GET'])]
    public function delete(Request $request, Employe $employe, EmployeRepository $employeRepository): Response
    {
        $form = $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'app_utilisateur_employe_delete'
                ,   [
                        'id' => $employe->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = true;
            $employeRepository->remove($employe, true);

            $redirect = $this->generateUrl('app_utilisateur_employe_index');

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

        return $this->renderForm('utilisateur/employe/delete.html.twig', [
            'employe' => $employe,
            'form' => $form,
        ]);
    }



    #[Route('/employe/addFile', name: 'employe_addFile_new', methods: ['GET', 'POST'])]
    public function addFile(Request $request,FormError $formError,EmployeRepository $employeRepository, EntityManagerInterface $entityManager,CiviliteRepository $civiliteRepository,FonctionRepository $fonctionRepository)
    {
        $dossier = new UploadFile();
        $form = $this->createForm(UploadFileType::class,$dossier, [
            'method' => 'POST',
            'action' => $this->generateUrl('employe_addFile_new')
        ]);
        $form->handleRequest($request);

        $data = null;
        $statutCode = Response::HTTP_OK;

        $isAjax = $request->isXmlHttpRequest();

        if ($form->isSubmitted()) {

            $response = [];
            $redirect = $this->generateUrl('app_utilisateur_employe_index');

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

                    $matricule = $Row['A'];     // store the first_name on each iteration
                    $nom = $Row['B'];     // store the first_name on each iteration
                    $prenom = $Row['C'];   // store the last_name on each iteration
                    $fonction= $Row['D'];  // store the email on each iteration
                    $contact = $Row['E']; // store the phone on each iteration
                    $adresse=$Row['F']; // store the phone on each iteration
                    $civilite = $Row['G']; // store the phone on each iteration

                    $employe_existant = $employeRepository->findOneBy(array('matricule' => $matricule));


                    // make sure that the user does not already exists in your db
                    if (!$employe_existant)
                    {

                        $employe = new Employe();
                        $employe->setNom($nom);
                        $employe->setMatricule($matricule);
                        $employe->setPrenom($prenom);
                        $employe->setContact($contact);
                        $employe->setAdresseMail($adresse);
                        if ($civilite)
                            $employe->setCivilite($civiliteRepository->findOneBy(array('libelle' => $civilite)));
                        if ($fonction)
                            $employe->setFonction($fonctionRepository->findOneBy(array('libelle' => $fonction)));
                        $entityManager->persist($employe);
                        $entityManager->flush();
                        // here Doctrine checks all the fields of all fetched data and make a transaction to the database.
                    }else{

                        $employe_existant->setNom($nom);
                        $employe_existant->setMatricule($matricule);
                        $employe_existant->setPrenom($prenom);
                        $employe_existant->setContact($contact);
                        $employe_existant->setAdresseMail($adresse);
                        if ($civilite)
                            $employe_existant->setCivilite($civiliteRepository->findOneBy(array('libelle' => $civilite)));
                        if ($fonction)
                            $employe_existant->setFonction($fonctionRepository->findOneBy(array('libelle' => $fonction)));
                        $entityManager->persist( $employe_existant);
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
