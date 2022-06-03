<?php declare(strict_types=1);

namespace Kadirov\Payme\Repository;

use Kadirov\Payme\Entity\PaymeTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method PaymeTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymeTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymeTransaction[]    findAll()
 * @method PaymeTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymeTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymeTransaction::class);
    }

//     /**
//      * @return PaymeTransaction[] Returns an array of PaymeTransaction objects
//      */
//    public function findByPaymeId(string $paymeId)
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.paymeId = :id')
//            ->setParameter('id', $paymeId)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    /**
     * @param string $paymeId
     * @return PaymeTransaction|null
     * @throws NonUniqueResultException
     */
    public function findOneByPaymeId(string $paymeId): ?PaymeTransaction
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.paymeId = :paymeId')
            ->setParameter('paymeId', $paymeId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
