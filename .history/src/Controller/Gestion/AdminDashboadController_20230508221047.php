<?php

namespace App\Controller\Gestion;

use App\Service\StatsService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminDashboadController extends AbstractController
{

    
    #[Route('/gestion/admin/dashboad/{debut}/{fin}', name: 'app_gestion_admin_dashboad')]
    public function index(EntityManagerInterface $mamager,StatsService $statsService,Request $request) :Response
    {
         
        
       
        
        $audiences = $statsService->getAudience('2023-04-09', '2023-05-10');
        // dd($audiences);
        // $audiences = $annRepo->countByDate();

        $request = $request->get('request');
        dd($request)

        $stats = $statsService->getStat();
       
        // dd($doneff);
        return $this->render('gestion/admindashboad/index.html.twig', [
            'stats' => $stats,
            'sdata'=> json_encode(["2 Jan", "9 Jan", "16 Jan", "23 Jan", "30 Jan", "6 Feb", "13 Feb"])
          
        ]);
    }
}
 