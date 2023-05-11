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

    p
    
    #[Route('/gestion/admin/dashboad', name: 'app_gestion_admin_dashboad')]
    public function index(EntityManagerInterface $mamager,StatsService $statsService,t)
    {
         
        
       
        
        $audiences = $statsService->getAudience('2023-04-09', '2023-05-10');
        // dd($audiences);
        // $audiences = $annRepo->countByDate();

        $c = $request->get('debut');
        dd($c);

        $stats = $statsService->getStat();
       
        // dd($doneff);
        return $this->render('gestion/admindashboad/index.html.twig', [
            'stats' => $stats,
            'sdata'=> json_encode(["2 Jan", "9 Jan", "16 Jan", "23 Jan", "30 Jan", "6 Feb", "13 Feb"])
          
        ]);
    }
}
 