<?php

namespace App\Repository;

use App\Entity\ParametreConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParametreConfiguration>
 *
 * @method ParametreConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParametreConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParametreConfiguration[]    findAll()
 * @method ParametreConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParametreConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParametreConfiguration::class);
    }

    public function save(ParametreConfiguration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ParametreConfiguration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function GetParametreByEntreprise($entreprise){
        return $this->createQueryBuilder('p')
           ->andWhere('p.entreprise = :val')
           ->setParameter('val', $entreprise)
            ->orderBy('p.id', 'ASC')
           ->setMaxResults(1)
          ->getQuery()
            ->getResult();
    }

//    /**
//     * @return ParametreConfiguration[] Returns an array of ParametreConfiguration objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ParametreConfiguration
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
