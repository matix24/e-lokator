<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Product::class);
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
        
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->innerJoin('p.category', 'c');
        $queryBuilder->innerJoin('p.manufacturer', 'm');

        # wyszukiwarka
        if(!is_null($searchValue) && $searchValue != ''){
            $searchValue = trim($searchValue);
            $queryBuilder->andWhere('p.product_name LIKE :searchTerm')->setParameter('searchTerm', '%'.$searchValue.'%');
        }

        # sortowanie
        if(!is_null($orderBy) && !empty($orderBy)){
            $chooseColumn = $columnsForOrder[$orderBy[0]['column']];
            $queryBuilder->orderBy($chooseColumn['name'], $orderBy[0]['dir']);
        }

        # filtrowanie
        if(!is_null($filterBy) && !empty($filterBy)){
            if($filterBy['id_category'] > 0){
                $queryBuilder->andWhere('c.id_category = :id_cat')->setParameter('id_cat', $filterBy['id_category']);
            }
            if($filterBy['id_manufacturer'] > 0){
                $queryBuilder->andWhere('m.id_manufacturer = :id_man')->setParameter('id_man', $filterBy['id_manufacturer']);
            }
            if(is_numeric($filterBy['product_price_from'])){
                $queryBuilder->andWhere('p.product_manufacturer_price >= :product_price_from')->setParameter('product_price_from', $filterBy['product_price_from']);
            }      
            if(is_numeric($filterBy['product_price_to'])){
                $queryBuilder->andWhere('p.product_manufacturer_price <= :product_price_to')->setParameter('product_price_to', $filterBy['product_price_to']);
            }
            if($filterBy['product_date_added_from'] != ''){
                $queryBuilder->andWhere('p.product_created_at >= :product_date_added_from')->setParameter('product_date_added_from', $filterBy['product_date_added_from'].":00");
            }   
            if($filterBy['product_date_added_to'] != ''){
                $queryBuilder->andWhere('p.product_created_at <= :product_date_added_to')->setParameter('product_date_added_to', $filterBy['product_date_added_to'].":59");
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
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}// end class
