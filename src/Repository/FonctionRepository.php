<?php

namespace App\Repository;

use App\Entity\Fonction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fonction>
 *
 * @method Fonction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fonction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fonction[]    findAll()
 * @method Fonction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FonctionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fonction::class);
    }

    public function add(Fonction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fonction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function getIdsChiffrage($offre, $etat = null)
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $existSQl = '';
       
        if ($etat == 'attribue') {
            
        }
        $sql = <<<SQL
SELECT valeur FROM `prj_element_colonne` ec
JOIN `prj_colonne` c ON c.id = ec.`colonne_id`

WHERE EXISTS (
    SELECT NULL
    FROM `prj_chiffrage_offre` o 
    WHERE o.`element_id` = ec.`element_id`
    AND o.offre_id = :offre_chiffrage
)
{$existSQl}
AND source = :source
SQL;
        
        $params['offre_chiffrage'] = $offre;
       
        $params['source'] = Fonction::class;
        $stmt = $connection->executeQuery($sql, $params);
        return $stmt->fetchFirstColumn();
    }

//    /**
//     * @return Fonction[] Returns an array of Fonction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fonction
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
