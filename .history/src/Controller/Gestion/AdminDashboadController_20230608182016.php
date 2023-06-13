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


    private $audienceannee =[];
    private $audiencesmoiscom =[];
    private $audiencesanncom =[];
    private $audiencesmoisloca =[];
    private $audienceanneloca =[];
    private $audiencemoiscateg =[];
    private $mois = [];
     private $y = [];

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
      
    }


    #[Route('/api/{id}', name: 'api_dashboad')]
    public function accueil(Request $request)
    {
       
        $this->id= $request->get('id');
        $id =$this->id;
       $this->audienceannee =  $this->statsService->getAudienceAnnee();
       $this->audiencesmoiscom =  $this->statsService->getAudienceMoisCom($id);
       $this->audiencesanncom =  $this->statsService->getAudienceAnCom($id);
       $this->audiencesmoisloca =  $this->statsService->getAudienceMoisLocalie($id);
       $this->audienceanneloca = $this->statsService->getAudienceAnlocalie($id);
       $this->audiencemoiscateg = $this->statsService->getAudienceMoisCetagorie($id);
    //    dd($this->statsService->getAudienceMoisCom($id));
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
        
    
        //$audiences = $this->statsService->getAudience('2023-04-09', '2023-05-10');
        $audienceyes = $this->statsService->getAudiencerealieyes();
        $audienceno = $this->statsService->getAudiencerealieno();
        $audiencemois = $this->statsService->getAudienceMois();

         $this->mois = [];
         $this->y = [];
        // $annees = [];
        // $somme = [];
        // $somme_annee = [];
       // if($this->statsService->getAudienceMoisCom(1))
        foreach ($this->audiencesmoiscom as $cam) {
           $this->mois[] = $cam['mois'];
            $this->y[]=$cam['nbre'];
            
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
            'mois'=> json_encode($this->; mois),
            'y'=> json_encode($y),
            'sdata'=> json_encode(["2 Jan", "9 Jan", "16 Jan", "23 Jan", "30 Jan", "6 Feb", "13 Feb"])
          
        ]);
    }
}
 