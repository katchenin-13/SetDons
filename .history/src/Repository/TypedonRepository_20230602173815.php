<?php

namespace App\Repository;

use App\Entity\Typedon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typedon>
 *
 * @method Typedon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typedon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typedon[]    findAll()
 * @method Typedon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypedonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typedon::class);
    }

    public function save(Typedon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Typedon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function  afficheModule($groupe)
    {
        return $this->createQueryBuilder('t')
            ->select('md.id', 'md.titre', 'md.ordre')
            //            ->where('m.groupeUser = : val')
            ->innerJoin('m.module', 'md')
            ->innerJoin('m.groupeUser', 'gu')
            ->andWhere('gu.id = :val')
            ->setParameter('val', $groupe)
            ->groupBy('md.id')
            ->orderBy('md.ordre', 'ASC')
            /*  ->setMaxResults(10)*/
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Typedon[] Returns an array of Typedon objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Typedon
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
