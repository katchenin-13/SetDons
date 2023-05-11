<?php

 namespace App\Service;

use App\Entity\Audience;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

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
    public function getStat(){
        $audiences = $this->getAudienceCount();
        $promesses = $this->getPromesseCount();
        $dons= $this->getDonCount();
        $doneffectues = $this->getDonEffectuerCount();

        return compact('audiences', 'promesses','dons', 'doneffectues');
    }
    /**m
     * cette fonction permet de compter tous les audience
     *
     * @return void
     */
    public function getAudienceCount(){
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\Audience u')->getSingleScalarResult();
    }
    
    /**
     * cette fonction permettant de  counter prmoesse
     *
     * @return void
     */
    public function getPromesseCount()
    {
        return $this->manager->createQuery('SELECT COUNT(d) FROM App\Entity\Don d WHERE d.promesse=1')->getSingleScalarResult();
    }
    /**
     * cette fonction permettant de compter les don
     *
     * @return void
     */
    public function getDonCount()
    {
        return $this->manager->createQuery('SELECT COUNt(d) FROM App\Entity\Don d')->getSingleScalarResult();
    }

    /**
     * cette fonction permet de compter les don effectués
     *
     * @return void
     */
 
    public function getDonEffectuerCount()
    {
        return $this->manager->createQuery('SELECT COUNt(d) FROM App\Entity\Don d WHERE d.statusdon=1 AND d.promesse=0')
            ->getSingleScalarResult();
    }

    public function countByDate()
    {
        // $query = $this->createQueryBuilder('a')
        //     ->select('SUBSTRING(a.created_at, 1, 10) as dateAnnonces, COUNT(a) as count')
        //     ->groupBy('dateAnnonces')
        // ;
        // return $query->getQuery()->getResult();
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
    // $query = $this->manager->createQuery('SELECT u FROM ForumUser u WHERE u.username = :name');
    return $this->manager->createQuery("SELECT COUNt(d) FROM App\Entity\Don AS d,App\Entity\Beneficiaire AS b,App\Entity\Communaute AS c
              WHERE d.id = b.don
              AND b.communaute = c.id AND d.CreatedAt >:debut AND d.CreatedAt <= :fin
             GROUP BY c.id")
            ->setParameter(':debut', $debut)
            ->setParameter(':fin', $fin)
          ->getResult();
    // $query->setParameter('name', 'Bob');
    // $users = $query->getResult(); // array of ForumUser objects
    }

    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getDonEffectuerCount1()
    {
        // return $this->manager->createQuery("SELECT COUNt(d) FROM App\Entity\Don AS d,App\Entity\Beneficiaire AS b,App\Entity\Communaute AS c
        //     WHERE d.id = b.don
        //     AND b.communaute = c.id AND d.CreatedAt BETWEEN 'c' AND
        //     '2023-05-10' 
        //     GROUP BY c.id")
        // ->getResult();
       
        
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
    public function getAudienceAnnee($cat,$debut,$fin)
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

    public function getAudienceMois()
    {

        $repo = $this->manager->getRepository(Dossier::class)->createQueryBuilder('a');
        return $repo->select('count(a.id)')
            ->Where("MONTH( NOW()) = MONTH(a.date_ouverture)")
            ->leftJoin('a.communaute', 'c')
            ->andWhere('c.id = :cat')
            ->getQuery()
            ->getSingleScalarResult();
    }

 }