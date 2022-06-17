<?php

namespace App\Repository;

use App\Entity\Apostador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Apostador>
 *
 * @method Apostador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Apostador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Apostador[]    findAll()
 * @method Apostador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApostadorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apostador::class);
    }

    public function add(Apostador $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Apostador $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getGanador($killsTotales) {
        $apuestaMasCercana = $this->createQueryBuilder('a')
            ->select("a, ABS(ap.kills - {$killsTotales}) AS HIDDEN diferencia")
            ->join("Apuesta", "a")
            ->orderBy("diferencia", "ASC")
            ->getQuery()
            ->getResult()[0]
        ;
        $this->getEntityManager()->refresh($apuestaMasCercana);

        return $apuestaMasCercana;
    }

//    /**
//     * @return Apostador[] Returns an array of Apostador objects
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

//    public function findOneBySomeField($value): ?Apostador
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
