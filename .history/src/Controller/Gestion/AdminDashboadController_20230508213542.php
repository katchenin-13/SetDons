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

    // /**
    //  * Undocumented function
    //  * @Route("/acte/{debut}/{fin}/active", name="acte_active", methods={"GET"})
    //  * @param Audience $parent
    //  * @param EntityManagerInterface $entityManager
    //  * @return Response
    //  */
    // public function active(Audience $parent, EntityManagerInterface $entityManager): Response
    // {

    //     //  dd($parent);
    //     if ($parent->isStatusaudience() == true) {

    //         $parent->setStatusaudience(false);
    //     } else {

    //         $parent->setStatusaudience(true);
    //     }
    //     // if ($parent->isMentions() == true) {

    //     //     $parent->setMentions(false);
    //     // } else {

    //     //     $parent->setMentions(true);
    //     // }

    //     $entityManager->persist($parent);
    //     $entityManager->flush();
    //     return $this->json([
    //         'code' => 200,
    //         'message' => 'ça marche bien',
    //         'active' => $parent->isStatusaudience(),
    //         // 'mention' =>$parent->isMentions(),
    //     ], 200);
    // }
    
    
    #[Route('/gestion/admin/dashboad', name: 'app_gestion_admin_dashboad')]
    public function index(EntityManagerInterface $mamager,StatsService $statsService) :Response
    {
         
        dd(re)
       
        
        $audiences = $statsService->getAudience('2023-04-09', '2023-05-10');
        // dd($audiences);
        // $audiences = $annRepo->countByDate();

       

        $stats = $statsService->getStat();
       
        // dd($doneff);
        return $this->render('gestion/admindashboad/index.html.twig', [
            'stats' => $stats,
            'sdata'=> json_encode(["2 Jan", "9 Jan", "16 Jan", "23 Jan", "30 Jan", "6 Feb", "13 Feb"])
          
        ]);
    }
}
 