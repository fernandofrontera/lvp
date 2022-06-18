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
}
