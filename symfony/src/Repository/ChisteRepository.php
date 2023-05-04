<?php

namespace App\Repository;

use App\Entity\Chiste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chiste>
 *
 * @method Chiste|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chiste|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chiste[]    findAll()
 * @method Chiste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChisteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chiste::class);
    }

    public function create(Chiste $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function update(Chiste $entity): void
    {
        $this->getEntityManager()->flush();
    }

    public function delete(Chiste $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return Chiste[] Returns an array of Chiste objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Chiste
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
