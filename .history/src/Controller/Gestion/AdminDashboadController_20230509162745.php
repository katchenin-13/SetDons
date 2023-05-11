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
    /**
     * Undocumented function
     *
     *@Route("/api",name="api",methods={"GET"})
     * @param EntityManagerInterface $mamager
     * @param StatsService $statsService
     * @param Request $request
     * @return Response
     */
    public function getaudience(EntityManagerInterface $mamager, StatsService $statsService, Request $request): Response
    {




       
        // dd($audiences);
        // $audiences = $annRepo->countByDate();

        $debut = $request->get('debut');
        $fin = $request->get('fin');
        $audiences = $statsService->getAudience($debut, $fin);
        dd($audiences);
        $data = [];
        foreach($audiences as $audience){
            $data = $audience['data'];

        }
        dd($data);$stats = $statsService->getStat();
       
    
        // dd($doneff);
        // return $this->json(
        //     json_encode($audiences)
        // );
         return $this->json([
            'code' => 200,
            'message' => 'ça marche bien',
            'audiences' => $audiences,
       ],200);
    }
    
    #[Route('/gestion/admin/dashboad', name: 'app_gestion_admin_dashboad')]
    public function index(EntityManagerInterface $mamager,StatsService $statsService,Request $request) :Response
    {
         
        
       
        
      
        // dd($audiences);
        // $audiences = $annRepo->countByDate();

        $c = $request->get('debut');
      

        $stats = $statsService->getStat();
       
        // dd($doneff);
        return $this->render('gestion/admindashboad/index.html.twig', [
            'stats' => $stats,
            'sdata'=> json_encode(["2 Jan", "9 Jan", "16 Jan", "23 Jan", "30 Jan", "6 Feb", "13 Feb"])
          
        ]);
    }
}
 