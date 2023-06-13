<?php

 namespace App\Service;

use App\Entity\Audience;
use App\Entity\Communaute;
use App\Entity\Don;
use App\Entity\Fieldon;
use App\Entity\Localite;
use App\Entity\Promesse;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectManager;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

use function PHPUnit\Framework\classHasAttribute;

 class StatsService {
    // private $manager;
    // public function __construct(ObjectManager $manager)
    // {
    //     $this->manager = $manager;
    // } 

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
       
        $this->manager = $manager;
     
        
    }

    public function getfieldon($don,$type)
    {
        return $this->manager->getRepository(Fieldon::class)->listFieldByGroup($don, $type);
    }
    public function Countfieldon()
    {
        return $this->manager->createQuery('SELECT COUNT(f) FROM App\Entity\Fieldton f')->getSingleScalarResult();
    }
    public function getStat( ){
        $audiences = $this->getAudienceCount();
        $promesses = $this->getPromesseCount();
        $promesseeff=$this->getPromesseCountRealise();
        $promessenoef = $this->getPromesseCountNorealise();
        $dons= $this->getDonCount();
        // $doneffectues = $this->getDonEffectuerCount();

        return compact(
            'audiences',
             'promesses',
            'promesseeff',
            'promessenoef',
             'dons');
    }

    ###########################################################################################
    #####################################LES REQUETRES LES repository############################
    
    public function ListeCommunaute()
    {
       return $this->manager->getRepository(Communaute::class)->find();
    }
    public function ListedesLocalie()
    {
        return $this->manager->getRepository(Localite::class)->find();
    }
    public function ListeDesCategorie()
    {
        return $this->manager->getRepository(Category::class)->find();
    }

    public function listeDesPromesse ()
    {
        return $this->manager->getRepository(Promesse::class)->findAll();
    }

    public function listeDesDons()
    {
        return $this->manager->getRepository(Don::class)->findAll();
    }

    public function listeDesAudience()
    {
        return $this->manager->getRepository(Audience::class)->findAll();
    }
    

    ###########################################################################################
    #####################################LES REQUETRES LES AUDIENCES############################
    /**
     * cette fonction permet de compter tous les audience
     *
     * @return void
     */
    public function getAudienceCount(){
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Audience a')->getSingleScalarResult();
    }

    public function getAudienceCountNo()
    {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Audience a WHERE a.statusaudience=0')->getSingleScalarResult();
    }

    public function getAudienceCountyes()
    {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Audience a WHERE a.statusaudience=1')->getSingleScalarResult();
    }
    
    /**
     * cette fonction permet de regrouper l
     *
     * @return void
     */
    public function getAudienceMois()
    {

        $repo = $this->manager->getRepository(Audience::class)->createQueryBuilder('a');
        return $repo->select('count(a.id)', "DATE_FORMAT(a.CreatedAt,'%M') as mois",)
            ->groupBy('mois')
           ->getQuery()
           ->getResult();
    }
    public function getAudienceAnnee()
    {

        $repo = $this->manager->getRepository(Audience::class)->createQueryBuilder('a');
        return $repo->select('count(a.id)', "DATE_FORMAT(a.CreatedAt,'%Y') as annee")
          
        ->groupBy('annee')
        ->getQuery()
            ->getResult();
    }
     

    /**
     * cette fonction permet de recuperer 
     *
     * @param [type] $id
     * @return void
     */
    public function getAudienceMoisCom($id)
    {

        $repo = $this->manager->getRepository(Audience::class)->createQueryBuilder('a');
        return $repo->select('count(a.id)', "DATE_FORMAT(a.CreatedAt,'%M') as mois","c.libelle")
        ->innerJoin('a.communaute', 'c')
        ->groupBy('mois')
        ->andWhere('c.id=:id')
        ->setParameter('id', $id)
        ->getQuery()
            ->getResult();
    }

    public function getAudienceAnCom($id)
    {

        $repo = $this->manager->getRepository(Audience::class)->createQueryBuilder('a');
        return $repo->select('count(a.id)', "DATE_FORMAT(a.CreatedAt,'%Y') as annee")
            ->innerJoin('a.communaute', 'c')
            ->groupBy('annee')
            ->andWhere('c.id:id')   
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
    

    // public function listFieldByGroup($don, $type): array
    // {
    //     return $this->createQueryBuilder('f')
    //         ->innerJoin('f.typedon', 't')
    //         ->innerJoin('f.don', 'd')
    //         ->andWhere('t.id= :type')
    //         ->andWhere('d.id= :don')
    //         ->setParameters(array('don' => $don, 'type' => $type))
    //         ->getQuery()
    //         ->getResult();
    // }

    ###########################################################################################
    #####################################LES REQUETRES LES PROMESSES############################
    
    /**
     * cette fonction permettant de  counter prmoesse
     *
     * @return void
     */
    public function getPromesseCount()
    {
        return $this->manager->createQuery('SELECT COUNT(p) FROM App\Entity\Promesse p ')->getSingleScalarResult();
    }


    public function getPromesseCountRealise()
    {
        return $this->manager->createQuery('SELECT COUNT(p) FROM App\Entity\Promesse p WHERE p.statusdon=1')->getSingleScalarResult();
    }

    public function getPromesseCountNorealise()
    {
        return $this->manager->createQuery('SELECT COUNT(p) FROM App\Entity\Promesse p  WHERE p.statusdon=0')->getSingleScalarResult();
    }


    ###########################################################################################
    #####################################LES REQUETRES LES DONS################################
    /**
     * cette fonction permettant de compter les don
     *
     * @return void
     */
    public function getDonCount()
    {
        return $this->manager->createQuery('SELECT COUNt(d) FROM App\Entity\Don d' )->getSingleScalarResult();
    }

    /**
     * CETTE FUNCTION PERMET DE COMPTER LES DONS PAR DATE
     *
     * @return void
     */
    public function countByDate()
    {
      $query = $this->manager->createQuery('SELECT SUBSTRING(a.CreatedAt, 1, 10) as dateaudiences, COUNT(a) as count FROM App\Entity\Audience a GROUP BY dateaudiences
        ');
        return $query->getResult();
    }


    /**
     * cette fonction permettre de compte des audiences entre deux date
     *
     * @return void
     */
    public function getAudience($debut ,$fin)
    {
    return $this->manager->createQuery('SELECT COUNT(a.id)  FROM App\Entity\Audience AS a,App\Entity\Communaute AS c
              WHERE c.id = a.communaute
               AND a.CreatedAt BETWEEN :debut AND :fin
                GROUP BY c.id')
                ->setParameter(':debut', $debut)
                ->setParameter(':fin', $fin)
                ->getResult();

    }

    

    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getDonEffectuerCount1()
    {
        return $this->manager->createQuery("SELECT COUNt(d) FROM App\Entity\Don AS d,App\Entity\Beneficiaire AS b,App\Entity\Communaute AS c
            WHERE d.id = b.don
            AND b.communaute = c.id AND d.CreatedAt BETWEEN '2023-04-09' AND
            '2023-05-10' 
            GROUP BY c.id")
        ->getResult();
       
        
    }

    // public function countByDate()
    // {
    //     // $query = $this->createQueryBuilder('a')
    //     //     ->select('SUBSTRING(a.created_at, 1, 10) as dateAnnonces, COUNT(a) as count')
    //     //     ->groupBy('dateAnnonces')
    //     // ;
    //     // return $query->getQuery()->getResult();
    //     $query = $this->getEntityManager()->createQuery("
    //         SELECT SUBSTRING(a.created_at, 1, 10) as dateAnnonces, COUNT(a) as count FROM App\Entity\Annonces a GROUP BY dateAnnonces
    //     ");
    //     return $query->getResult();
    // }

    // /**
    //  * Returns Annonces between 2 dates
    //  */
    // public function selectInterval($from, $to, $cat = null)
    // {
    //     // $query = $this->getEntityManager()->createQuery("
    //     //     SELECT a FROM App\Entity\Annonces a WHERE a.created_at > :from AND a.created_at < :to
    //     // ")
    //     //     ->setParameter(':from', $from)
    //     //     ->setParameter(':to', $to)
    //     // ;
    //     // return $query->getResult();
    //     $query = $this->createQueryBuilder('a')
    //         ->where('a.created_at > :from')
    //         ->andWhere('a.created_at < :to')
    //         ->setParameter(':from', $from)
    //         ->setParameter(':to', $to);
    //     if ($cat != null) {
    //         $query->leftJoin('a.categories', 'c')
    //             ->andWhere('c.id = :cat')
    //             ->setParameter(':cat', $cat);
    //     }
    //     return $query->getQuery()->getResult();
    // }

    // /**
    //  * Returns all Annonces per page
    //  * @return void 
    //  */
    // public function getPaginatedAnnonces($page, $limit, $filters = null)
    // {
    //     $query = $this->createQueryBuilder('a')
    //         ->where('a.active = 1');

    //     // On filtre les données
    //     if ($filters != null) {
    //         $query->andWhere('a.categories IN(:cats)')
    //             ->setParameter(':cats', array_values($filters));
    //     }

    //     $query->orderBy('a.created_at')
    //         ->setFirstResult(($page * $limit) - $limit)
    //         ->setMaxResults($limit);
    //     return $query->getQuery()->getResult();
    // }
   

    /**
     * cette fonction permet de retourner les audiance par date de creation groupe par categorie
     *
     * @param [type] $cat
     * @param [type] $debut
     * @param [type] $fin
     * @return void
     */
    public function getAudienceAnnee1($cat,$debut,$fin)
    {

        $repo = $this->manager->getRepository(Audience::class)->createQueryBuilder('a');
        return $repo->select('count(a.id),a.statusaudience,a.created_at') 
                   ->andWhere('a.statusaudience = 1');
           
            // ->Where("YEAR( NOW()) = YEAR(a.daterencontre)")
            if ($debut != null and $fin !=null) {
            $repo ->where('a.created_at > :debut')
                ->andWhere('a.created_at < :fin')
                ->setParameter('debut', $debut)
                ->setParameter('fin', $fin);
            }else {
              $repo->Where("YEAR( NOW()) = YEAR(a.daterencontre)");
            }
            if ($cat != null) {
                $repo->leftJoin('a.communaute', 'c')
                    ->andWhere('c.id = :cat')
                    ->setParameter('cat', $cat);
            
          }
        $repo->getQuery()->getSingleScalarResult();
    }

    public function getAudienceMois1()
    {

        $repo = $this->manager->getRepository(Dossier::class)->createQueryBuilder('a');
        return $repo->select('count(a.id)')
            ->Where("MONTH( NOW()) = MONTH(a.date_ouverture)")
            ->leftJoin('a.communaute', 'c')
            ->andWhere('c.id = :cat')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function nombre_bordereaux_annee(){

        $repo = $this->em->getRepository(Bordereau::class)->createQueryBuilder('b');
        return $repo->select('count(b.id)')
            ->Where("YEAR( NOW()) = YEAR(b.date_bordereau)")
            ->getQuery()
            ->getSingleScalarResult();


    }

    public function nombre_dossiers_mois(){

        $repo = $this->em->getRepository(Dossier::class)->createQueryBuilder('d');
        return $repo->select('count(d.id)')
            ->Where("MONTH( NOW()) = MONTH(d.date_ouverture)")
            ->getQuery()
            ->getSingleScalarResult();


    }

    public function nombre_dossiers_annee(){

        $repo = $this->em->getRepository(Dossier::class)->createQueryBuilder('d');
        return $repo->select('count(d.id)')
            ->Where("YEAR( NOW()) = YEAR(d.date_ouverture)")
            ->getQuery()
            ->getSingleScalarResult();


    }

    public function chiffre_affaire_mois(){

        $repo = $this->em->getRepository(Facture::class)->createQueryBuilder('f');
        return $repo->select('sum(f.montant)')
            ->Where("MONTH( NOW()) = MONTH(f.date_facture) and f.lettre is not null")
            ->getQuery()
            ->getSingleScalarResult();


    }

    public function chiffre_affaire_annee(){

        $repo = $this->em->getRepository(Facture::class)->createQueryBuilder('f');
        return $repo->select('sum(f.montant)')
            ->Where("YEAR( NOW()) = YEAR(f.date_facture) and f.lettre is not null")
            ->getQuery()
            ->getSingleScalarResult();


    }

    public function client_tous_annee(){

        $repo = $this->em->getRepository(ClientCompte::class)->createQueryBuilder('c');
        return $repo->select('count(c.id)')
            ->Where("YEAR( NOW()) = YEAR(c.date_creation)")
            ->getQuery()
            ->getSingleScalarResult();


    }

    public function client_nouveaux(){

        $repo = $this->em->getRepository(ClientCompte::class)->createQueryBuilder('c');
        return $repo->select('count(c.id)')
            ->Where("MONTH( NOW()) = MONTH(c.date_creation)")
            ->getQuery()
            ->getSingleScalarResult();

}

 }