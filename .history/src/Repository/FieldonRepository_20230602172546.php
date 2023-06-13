<?php

namespace App\Repository;

use App\Entity\Fieldon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fieldon>
 *
 * @method Fieldon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fieldon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fieldon[]    findAll()
 * @method Fieldon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fieldon::class);
    }

    public function save(Fieldon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fieldon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function listFieldByGroup(): array
   {
       return $this->createQueryBuilder('f')
            ->groupBy('f.typedon')
            ->groupBy('f.typedon')
            ->groupBy('f.typedon')
           ->getQuery()
           ->getResult()
       ;
   }
//    /**
//     * @return Fieldon[] Returns an array of Fieldon objects
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

//    public function findOneBySomeField($value): ?Fieldon
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
