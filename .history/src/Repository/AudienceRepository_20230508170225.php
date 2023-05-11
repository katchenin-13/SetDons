<?php

namespace App\Repository;

use App\Entity\Audience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Audience>
 *
 * @method Audience|null find($id, $lockMode = null, $lockVersion = null)
 * @method Audience|null findOneBy(array $criteria, array $orderBy = null)
 * @method Audience[]    findAll()
 * @method Audience[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AudienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Audience::class);
    }

    public function save(Audience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Audience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    

     /**
     * @return Audience[] Returns an array of Audience objects
     */
    public function SelectInterval($form,$to,$commu=null)
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.CreatedAt > :form')
            ->andWhere('a.CreatedAt < :to')
            ->orderBy('a.id', 'ASC')
            ->setParameter(':form',$form)
            ->setParameter(':to',$to);
         if ($commu!= null) {
           $query->leftJoin('a.communaute','c')
            
           ->andWhere('c.id = :commu')
            ->setParameter(':commu', $to);
         }   
         return $query->getQuery()->getResult();
    }
   

//    /**
//     * @return Audience[] Returns an array of Audience objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Audience
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
