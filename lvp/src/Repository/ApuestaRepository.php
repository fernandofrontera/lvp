<?php

namespace App\Repository;

use App\Entity\Apuesta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Apuesta>
 *
 * @method Apuesta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Apuesta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Apuesta[]    findAll()
 * @method Apuesta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApuestaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apuesta::class);
    }

    public function add(Apuesta $entity, bool $flush = false): Apuesta
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();

        try {
            $em->persist($entity);

            if ($flush) {
                $em->flush();
            }
            $em->commit();
            return $entity;
        } catch(\Exception $e) {
            $em->rollback();
            throw $e;
        }
    }

    public function remove(Apuesta $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPromedioKills() {
        return $this->createQueryBuilder('a')
            ->select("AVG(a.kills) AS promedio")
            ->getQuery()
            ->getOneOrNullResult()
        ["promedio"];
    }

    public function getApuestaMasCercana(int $valor): ?Apuesta {
        return $this->createQueryBuilder("a")
            ->addSelect("g, ABS(a.kills - {$valor}) AS HIDDEN distancia")
            ->join("a.apostador", "g")
            ->orderBy("distancia", "ASC")
            ->getQuery()
            ->getResult()[0]
        ;
    }

//    /**
//     * @return Apuesta[] Returns an array of Apuesta objects
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

//    public function findOneBySomeField($value): ?Apuesta
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
