<?php

namespace App\Repository;

use App\Entity\ModuleGroupePermition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModuleGroupePermition>
 *
 * @method ModuleGroupePermition|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleGroupePermition|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleGroupePermition[]    findAll()
 * @method ModuleGroupePermition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleGroupePermitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleGroupePermition::class);
    }

    public function save(ModuleGroupePermition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ModuleGroupePermition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function  getPermission($groupe,$lien){
        return $this->createQueryBuilder('m')
            ->select('p.code')
//            ->where('m.groupeUser = : val')
            ->innerJoin('m.permition','p')
            ->innerJoin('m.groupeModule','gm')
            ->innerJoin('m.groupeUser','gu')
            ->andWhere('gm.lien = :lien')
            ->andWhere('gu.id = :val')
            ->setParameters(['val'=> $groupe,'lien'=>$lien])
            /*  ->setMaxResults(10)*/
            ->getQuery()
            ->getSingleResult();
    }

    public function  afficheModule($groupe){
        return $this->createQueryBuilder('m')
            ->select('md.id','md.titre','md.ordre')
//            ->where('m.groupeUser = : val')
            ->innerJoin('m.module','md')
            ->innerJoin('m.groupeUser','gu')
            ->andWhere('gu.id = :val')
            ->setParameter('val', $groupe)
            ->groupBy('md.id')
            ->orderBy('md.ordre', 'ASC')
          /*  ->setMaxResults(10)*/
            ->getQuery()
            ->getResult();
        }
    public function affiche($params)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = "
            SELECT m.groupe_module_id,m.module_id,g.titre,g.lien
            FROM module_groupe_permition m
          /*  INNER JOIN module as md on m.module_id=md.id*/
            INNER JOIN groupe_module as g on g.id=m.groupe_module_id
            INNER JOIN user_groupe as gu on gu.id=m.groupe_user_id
          /*   group by m.groupe_module_id*/
          where gu.id = $params 
             order by m.ordre_groupe ASC
           
            ";
       /* $stmt = $conn->executeQuery($sql);
        return $stmt->fetchAll();*/
        $stmt = $conn->executeQuery($sql);
        return $stmt->fetchAllAssociative();

    }
    public function  afficheGroupe(){
        return $this->createQueryBuilder('m')
            ->select('g.id','g.titre','g.ordre','md.id')
            ->innerJoin('m.groupeModule','g')
            ->leftJoin('m.module','md')
           /* ->innerJoin('m.module','md')*/
           /* ->groupBy('g.id')*/
            ->orderBy('g.ordre', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return ModuleGroupePermition[] Returns an array of ModuleGroupePermition objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ModuleGroupePermition
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
