<?php

namespace App\Controller\Gestion;

use App\Entity\Audience;
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
use Symfony\Component\Security\Core\Security;

class AdminDashboadController extends AbstractController
 {



    /**
     * Undocumented function
     *@Route("/api/{id}/choix", name="api_name",methods={"GET"})
     * @param Security $security
     * @param Request $request
     * @param Audience $audience
     * @return void
     */
    public function accueil(Security $security, Request $request, Audience $audience)
    {
        //$datefin = $mission->getDatefin();
        $id = $request->get('id');
        
        return $this->json([
            'code' => 200,
            'message' => 'ça marche bien',
            'id' => $id,

        ], 200);

        // return $this->render('gestion/admindashboad/index.html.twig', [
        //     //  'stats' => $stats,
        //     'audiencemois'=> $audiencemois,
        //     'mois'=> json_encode($mois),
        //     'y'=> json_encode($y),
        //     'sdata'=> json_encode(["2 Jan", "9 Jan", "16 Jan", "23 Jan", "30 Jan", "6 Feb", "13 Feb"])
          
        // ]);
    }
 
    
    #[Route('/gestion/admin/dashboad', name: 'app_gestion_admin_dashboad')]
    public function index(EntityManagerInterface $mamager,StatsService $statsService,Request $request) :Response
    {

        $id = $request->get('id');
                 
        $audiences = $statsService->getAudience('2023-04-09', '2023-05-10');
        $audienceyes = $statsService->getAudiencerealieyes();
        $audienceno =$statsService->getAudiencerealieno();
        $audiencemois =$statsService->getAudienceMois();

        //dd( $statsService->getAudienceMois());
        // $audienceannee = $statsService->getAudienceAnnee();
        // $audiencesmoiscom = $statsService->getAudienceMoisCom();
        // $audiencesanncom = $statsService ->getAudienceAnCom();
        // $audiencesmoisloca = $statsService->g  etAudienceMoisLocalie();
        // $audienceanneloca =$statsService->getAudienceAnlocalie();
        // $$audiencemoiscateg =$statsService->getAudienceMoisCetagorie();
        // dd($audiences);
        // $audiences = $annRepo->countByDate();
        //$soro = $statsService->getAudienceAnnee();
        //dd($soro);

       // $c = $request->get('debut');
         $id = $request->get("id");
        $audiencesmoiscom = $statsService->getAudienceMoisCom(1);

        // $stats = $statsService->getAudienceAnlocalie(1);
        // dd($stats);

        //$stats = $statsService->getStat();

        // dd($doneff);

         $mois = [];
         $y = [];
        // $annees = [];
        // $somme = [];
        // $somme_annee = [];

        foreach ($audiencesmoiscom as $cam) {
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
 