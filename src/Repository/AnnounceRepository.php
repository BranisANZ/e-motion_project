<?php

namespace App\Repository;

use App\Entity\Announce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Announce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Announce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Announce[]    findAll()
 * @method Announce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnounceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Announce::class);
    }

    /**
     * @return Announce[] Returns an array of Announce objects
     */
    public function findForSearch($value)
    {
        $search = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
        ;

        if($value['minPrice'] != null){
            $search->andWhere('a.price >= :priceMini')
                ->setParameter('priceMini',$value['minPrice'])
            ;
        }
        if($value['maxPrice'] != null){
            $search->andWhere('a.price <= :maxPrice')
                ->setParameter('maxPrice',$value['maxPrice'])
            ;
        }
        dump($search);
        return $search->getQuery()->getResult();


    }


    /*
    public function findOneBySomeField($value): ?Announce
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}