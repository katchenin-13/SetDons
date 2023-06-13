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

    private $id;
    private $statsService;
    private $request;

    private $audienceannee ;
    private $audiencesmoiscom;
    private $audiencesanncom ;
    private $audiencesmoisloca;
    private $audienceanneloca;
    private $audiencemoiscateg;

    public function __construct(StatsService $statsService, Request $request)
    {
        $this->statsService = $statsService;
        $this->request  = $request;
    }


    #[Route('/api/{id}', name: 'api_dashboad')]
    public function accueil()
    {
       
        $this->id= $this->request->get('id');
        $id =$this->id;
      $this->audienceannee =  $this->statsService->getAudienceAnnee();
       $this->audiencesmoiscom =  $this->statsService->getAudienceMoisCom($id);
       $this->audiencesanncom =  $this->statsService->getAudienceAnCom($id);
       $this->audiencesmoisloca =  $this->statsService->getAudienceMoisLocalie($id);
       $this->audienceanneloca = $this->statsService->getAudienceAnlocalie($id);
       $this->audiencemoiscateg = $this->statsService->getAudienceMoisCetagorie($id);
       
        return $this->json([
            'code' => 200,
            'message' => 'ça marche bien',
            'id' => $this->id,

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
    public function index(EntityManagerInterface $mamager) :Response
    {
        $request = $this->request;
        $id = $request->get('id');
       // dd('dgfghgfgh');
                 
        //$audiences = $this->statsService->getAudience('2023-04-09', '2023-05-10');
        $audienceyes = $this->statsService->getAudiencerealieyes();
        $audienceno = $this->statsService->getAudiencerealieno();
        $audiencemois = $this->statsService->getAudienceMois();

        //dd( $statsService->getAudienceMois());
        // 
        // dd($audiences);
        // $audiences = $annRepo->countByDate();
        //$soro = $statsService->getAudienceAnnee();
        //dd($soro);

      
     

        // $stats = $statsService->getAudienceAnlocalie(1);
        // dd($stats);

        //$stats = $statsService->getStat();

        // dd($doneff);

         $mois = [];
         $y = [];
        // $annees = [];
        // $somme = [];
        // $somme_annee = [];
       // if($this->statsService->getAudienceMoisCom(1))
        foreach ($this->statsService->getAudienceMoisCom($this->id) as $cam) {
            $mois[] = $cam['mois'];
            $y[]=$cam['nbre'];
            
        }
        ///fdd($mois);

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
 