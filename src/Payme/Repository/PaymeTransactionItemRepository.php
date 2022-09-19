<?php

namespace Kadirov\Payme\Repository;

use Kadirov\Payme\Entity\PaymeTransactionItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymeTransactionItem>
 *
 * @method PaymeTransactionItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymeTransactionItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymeTransactionItem[]    findAll()
 * @method PaymeTransactionItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymeTransactionItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymeTransactionItem::class);
    }

    public function add(PaymeTransactionItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PaymeTransactionItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PaymeTransactionItem[] Returns an array of PaymeTransactionItem objects
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

//    public function findOneBySomeField($value): ?PaymeTransactionItem
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
