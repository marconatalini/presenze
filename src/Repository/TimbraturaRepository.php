<?php

namespace App\Repository;

use App\Entity\Timbratura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Timbratura|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timbratura|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timbratura[]    findAll()
 * @method Timbratura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimbraturaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timbratura::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Timbratura $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Timbratura $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Timbratura[] array of timbrature
     */
    public function findTimbrature($start, $end, $codice)
    {
        $qb = $this->createQueryBuilder('t');

        return $qb->andWhere('t.timestamp BETWEEN :start AND :end')
            ->andWhere('t.codice = :codice')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('codice', $codice)
            ->select('t.id','t.timestamp as start', 't.direzione as title')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_SCALAR);
    }

    /**
     * @return mixed
     */
    public function getPresenti()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT t1.codice, t1.timestamp, t1.terminale from timbratura t1 join
(SELECT max(id) as maxId, codice FROM timbratura where timestamp >= current_date GROUP BY  codice) t2
on (t1.id = t2.maxId) where t1.direzione = 0";
        $stmt = $conn->prepare($sql);
//        $stmt->bindValue(1, $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // /**
    //  * @return Timbratura[] Returns an array of Timbratura objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Timbratura
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
