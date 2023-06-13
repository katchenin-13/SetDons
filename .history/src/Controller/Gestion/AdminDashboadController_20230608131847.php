<?php

namespace App\Controller\Gestion;

use App\Repository\CommunauteRepository;
use App\Service\StatsService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class AdminDashboadController extends AbstractController
 {
    /**
     * Undocumented function
     *@Route("/acte/{id}/active", name="acte_active", methods={"GET"})
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
        //dd($audiences);
        // $data = [];
        // foreach($audiences as $audience){
        //    $data =$audience['CreatedAt'];
        // }
        // $stats = $statsService->getAudienceMoisCom(1);
        // dd($stats);
        // return $this->json(
        //     json_encode($audiences)
        // );
         return $this->json([
            'code' => 200,
            'message' => 'ça marche bien',
           
           
            // 'mention' =>$parent->isMentions(),
       ],200);
    }
    
    #[Route('/gestion/admin/dashboad', name: 'app_gestion_admin_dashboad')]
    public function index(EntityManagerInterface $mamager,StatsService $statsService,Request $request) :Response
    {
                 
        $audiences = $statsService->getAudience('2023-04-09', '2023-05-10');
        $audienceyes = $statsService->getAudiencerealieyes();
        $audienceno =$statsService->getAudiencerealieno();
        $audiencemois =$statsService->getAudienceMois();

        //dd( $statsService->getAudienceMois());
        // $audienceannee = $statsService->getAudienceAnnee();
        // $audiencesmoiscom = $statsService->getAudienceMoisCom();
        // $audiencesanncom = $statsService ->getAudienceAnCom();
        // $audiencesmoisloca = $statsService->getAudienceMoisLocalie();
        // $audienceanneloca =$statsService->getAudienceAnlocalie();
        // $$audiencemoiscateg =$statsService->getAudienceMoisCetagorie();
        // dd($audiences);
        // $audiences = $annRepo->countByDate();
        //$soro = $statsService->getAudienceAnnee();
        //dd($soro);

        $c = $request->get('debut');

        // $stats = $statsService->getAudienceAnlocalie(1);
        // dd($stats);

        //$stats = $statsService->getStat();

        // dd($doneff);

         $mois = [];
         $y = [];
        // $annees = [];
        // $somme = [];
        // $somme_annee = [];

        foreach ($statsService->getAudienceMois() as $cam) {
            $mois[] = $cam['mois'];
            $y[]=$cam['nbre'];
            
        }
        //dd($mois);

        // foreach ($statistique->chiffre_affaire_annee_bar() as $caa) {
        //     $annees[] = $caa['annee'];
        //     $somme_annee[] = $caa['montant'];
        // }

        // return $this->render('home/home.html.twig', [
        //     'mois' => json_encode($mois),
        //     'somme' => json_encode($somme),
        //     'somme_annee' => json_encode($somme_annee),
        //     'annees' => json_encode($annees),
        // ]);
        return $this->render('gestion/admindashboad/index.html.twig', [
            //  'stats' => $stats,
            'audiencemois'=> $audiencemois,
            'mois'=> json_encode($mois),
            'y'=> json_encode($y),
            'sdata'=> json_encode(["2 Jan", "9 Jan", "16 Jan", "23 Jan", "30 Jan", "6 Feb", "13 Feb"])
          
        ]);
    }
}
 