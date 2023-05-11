<?php

namespace App\Controller\Gestion;

use App\Service\StatsService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\ORM\EntityManagerInterface;

class AdminDashboadController extends AbstractController
{
    
   
    #[Route('/gestion/admin/dashboad', name: 'app_gestion_admin_dashboad')]
    public function index(EntityManagerInterface $mamager,StatsService $statsService)
    {
         
        // $audiences = $statsService->getAudienceCount();
        // $promesses = $statsService->getPromesseCount();
        // $don = $statsService->getDonCount();
        // $doneff = $statsService->getDonEffectuerCount();
        $audiences = $statsService->countByDate();
        // dd($audiences);
        // $audiences = $annRepo->countByDate();

        $dates = [];
        $audiencesCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach ($audiences as $audience) {
            $dates[] = $audience['dateaudiences'];
            $audiencesCount[] = $audience['count'];
        }
        dd(audiencesCount)
        // return $this->render('admin/stats.html.twig', [
        //         // 'categNom' => json_encode($categNom),
        //         // 'categColor' => json_encode($categColor),
        //         // 'categCount' => json_encode($categCount),
        //         // 'dates' => json_encode($dates),
        //         'audiencesCount' => json_encode($audiencesCount),
        //     ]);

        $stats = $statsService->getStat();
       
        // dd($doneff);
        return $this->render('gestion/admindashboad/index.html.twig', [
            'stats' => $stats,
            'audiencesCount' => json_encode($audiencesCount),
        ]);
    }
}
 