<?php

namespace App\Repository;

use App\Entity\ManufacturerForPartner;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ManufacturerForPartner|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManufacturerForPartner|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManufacturerForPartner[]    findAll()
 * @method ManufacturerForPartner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManufacturerForPartnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ManufacturerForPartner::class);
    }

    /**
     * Paginator dla DataTable
     * @param int $page
     * @param int $itemPerPage
     * @param string|null $searchValue
     * @param array|null $filterBy
     * @param array|null $orderBy
     * @param array|null $columnsForOrder
     * @return Paginator
     */
    public function fetchForPaginator(int $page, int $itemPerPage, ?string $searchValue = null, ?array $filterBy = array(), ?array $orderBy = array(), ?array $columnsForOrder = array()): Paginator{
        
        $queryBuilder = $this->createQueryBuilder('mfp');
        $queryBuilder->innerJoin('mfp.partner', 'p');
        $queryBuilder->innerJoin('mfp.manufacturer', 'm');

        # wyszukiwarka
        if(!is_null($searchValue) && $searchValue != ''){
            $searchValue = trim($searchValue);
            $queryBuilder->andWhere('mfp.mfp_updated_at LIKE :searchTerm')->setParameter('searchTerm', '%'.$searchValue.'%');
        }

        # sortowanie
        if(!is_null($orderBy) && !empty($orderBy)){
            $chooseColumn = $columnsForOrder[$orderBy[0]['column']];
            $queryBuilder->orderBy($chooseColumn['name'], $orderBy[0]['dir']);
        }

        # filtrowanie
        if(!is_null($filterBy) && !empty($filterBy)){
            if($filterBy['id_partner'] > 0){
                $queryBuilder->andWhere('p.id_partner = :id_partner')->setParameter('id_partner', $filterBy['id_partner']);
            }
            if($filterBy['id_manufacturer'] > 0){
                $queryBuilder->andWhere('m.id_manufacturer = :id_manufacturer')->setParameter('id_manufacturer', $filterBy['id_manufacturer']);
            }                                          
        }

        $paginator = new Paginator($queryBuilder->getQuery());
        $paginator
            ->getQuery()
            ->setFirstResult($itemPerPage * ($page-1))
            ->setMaxResults($itemPerPage);

        return $paginator;
    }// end fetchForPaginator

    // /**
    //  * @return ManufacturerForPartner[] Returns an array of ManufacturerForPartner objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ManufacturerForPartner
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}// end class
