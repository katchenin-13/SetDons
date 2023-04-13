<?php

namespace App\Controller\Configuration;

use App\Repository\AgendaRepository;
use App\Service\Breadcrumb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/config/agenda')]
class   AgendaController extends AbstractController
{

    #[Route(path: '/', name: 'app_config_agenda_index', methods: ['GET', 'POST'])]
    public function index(Request $request, Breadcrumb $breadcrumb,AgendaRepository $agendaRepository): Response
    {
        $modules = [
                        // [
                        //     'label' => 'Agenda',
                        //     'icon' => 'bi bi-people',
                        //     'href' => $this->generateUrl('app_gestion_calendrier', ['module' => 'agenda'])
                        // ],
                        [
                            'label' => 'Liste des évènements',
                            'icon' => 'bi bi-people',
                            'href' => $this->generateUrl('app_gestion_agenda_index', ['module' => 'evenement'])
                        ],
        ];

        $breadcrumb->addItem([
            [
                'route' => 'app_default',
                'label' => 'Tableau de bord'
            ],
            [
                'label' => 'Paramètres'
            ]
        ]);


        return $this->render('config/agenda/index.html.twig', [
            'modules' => $modules,
            'breadcrumb' => $breadcrumb
          
        ]);
    }


    #[Route(path: '/{module}', name: 'app_config_agenda_ls', methods: ['GET', 'POST'])]
    public function liste(Request $request, string $module): Response
    {
        /**
         * @todo: A déplacer dans un service
         */
        $parametes = [

            'agenda' => [
                     
                     'label' => 'Agenda',
                     'id' => 'param_audience',
                     'href' => $this->generateUrl('app_gestion_calendrier')
                ] ,
                
           'evenement' => [
                     
                    'label' => 'Liste des évènements',
                    'id' => 'param_r',
                    'href' => $this->generateUrl('app_config_agenda_index')
               ] ,
        ];


        return $this->render('config/agenda/liste.html.twig', ['links' => $parametes[$module] ?? []]);
    }
}