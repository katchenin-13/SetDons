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


#[Route('/gestion/admin/dashboad')]
class AdminDashboadController extends AbstractController
 {

     private $id;
    private $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    #[Route('/api/{id}', name: 'api_dashboad',methods: ['GET', 'POST'])]
    public function accueil(Security $security, Request $request):re
    {
        $this->id= $request->get('id');
        //dd($this->id);
        return $this->json([
            'code' => 200,
            'message' => 'ça marche bien',
            'id'=>$this->id   ], 200);
    }
 
    
    #[Route('/', name: 'app_gestion_admin_dashboad',methods: ['GET', 'POST'])]
    public function index() :Response
    {
      
       dd($this->id);
   
  
            
                
        $audiences = $this->statsService->getAudience('2023-04-09', '2023-05-10');
        $audienceyes = $this->statsService->getAudiencerealieyes();
        $audienceno = $this->statsService->getAudiencerealieno();
        $audiencemois = $this->statsService->getAudienceMois();

       
        // $audienceannee = $statsService->getAudienceAnnee();
        // $audiencesmoiscom = $statsService->getAudienceMoisCom();
        // $audiencesanncom = $statsService ->getAudienceAnCom();
        // $audiencesmoisloca = $statsService->g  etAudienceMoisLocalie();
        // $audienceanneloca =$statsService->getAudienceAnlocalie();
        // $$audiencemoiscateg =$statsService->getAudienceMoisCetagorie();
        // dd($audiences);
     


         $mois = [];
         $y = [];
        //   dd($this->id);
        // $annees = [];
        // $somme = [];
        // $somme_annee = [];
        // if($this->statsService->getAudienceMoisCom(1))
        foreach ($this->statsService->getAudienceMoisCom($this->id) as $cam) {
            
            $mois[] = $cam['mois'];
            $y[] = $cam['nbre'];
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
            'mois'=> json_encode($mois),
            'y'=> json_encode($y),
            'sdata'=> json_encode(["2 Jan", "9 Jan", "16 Jan", "23 Jan", "30 Jan", "6 Feb", "13 Feb"])
          
        ]);
    }
}
 