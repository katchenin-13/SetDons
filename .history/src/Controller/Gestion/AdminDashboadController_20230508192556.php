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
    
    
    public function active(Audience $parent, EntityManagerInterface $entityManager): Response
    {

        //  dd($parent);
        if ($parent->isStatusaudience() == true) {

            $parent->setStatusaudience(false);
        } else {

            $parent->setStatusaudience(true);
        }
        // if ($parent->isMentions() == true) {

        //     $parent->setMentions(false);
        // } else {

        //     $parent->setMentions(true);
        // }

        $entityManager->persist($parent);
        $entityManager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'ça marche bien',
            'active' => $parent->isStatusaudience(),
            // 'mention' =>$parent->isMentions(),
        ], 200);
    }
    #[Route('/gestion/admin/dashboad', name: 'app_gestion_admin_dashboad')]
    public function index(EntityManagerInterface $mamager,StatsService $statsService)
    {
         
        // $audiences = $statsService->getAudienceCount();
        // $promesses = $statsService->getPromesseCount();
        // $don = $statsService->getDonCount();
        // $doneff = $statsService->getDonEffectuerCount();
        $audiences = $statsService->getAudience('2023-04-09', '2023-05-10');
        // dd($audiences);
        // $audiences = $annRepo->countByDate();

        $dates = [];
        $audiencesCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach ($audiences as $audience) {
            $dates[] = $audience['dateaudiences'];
            $audiencesCount[] = $audience['count'];
        }
        
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
 