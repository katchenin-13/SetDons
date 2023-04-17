<?php

namespace App\Controller;

use DateTime;
use App\Entity\Agenda;
use App\Repository\AgendaRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'api')]
    public function index(): Response
    {   
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
    /**
     * @[Route("/api/{id}/edit",name="api_event_edit", methods={"PUT"})]
     */
    public function majEvent(?Agenda $agenda, Request $request, AgendaRepository $agendaRepository)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if(
            isset($donnees->d) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On vérifie si l'id existe
            if(!$agenda){
                // On instancie un rendez-vous
                $agenda = new Agenda;

                // On change le code
                $code = 201;
            }

            // On hydrate l'objet avec les données
            $agenda->setLibelle($donnees->title);
            $agenda->setDescription($donnees->description);
            $agenda->setStart(new DateTime($donnees->start));
            if($donnees->allDay){
                $agenda->setEnd(new DateTime($donnees->start));
            }else{
                $agenda->setEnd(new DateTime($donnees->end));
            }
            $agenda->setAllDay($donnees->allDay);
            $agenda->setBackgroundColor($donnees->backgroundColor);
            $agenda->setBorderColor($donnees->borderColor);
            $agenda->setTextColor($donnees->textColor);

           // $em = $this->getDoctrine()->getManager();
           $agendaRepository->save($agenda,true);
             
            // $entityManager->persist($agenda);
            // $entityManager->flush();

            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }


        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
 