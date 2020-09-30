<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository { 

    public function __construct(ManagerRegistry $registry){
        parent::__construct($registry, Category::class);
    }

    /**
     * Paginator kategorii produktów
     * @param int $page
     * @param int $itemPerPage
     * @param string|null $searchValue
     * @param array|null $filterBy
     * @param array|null $orderBy
     * @param array|null $columnsForOrder
     * @return Paginator
     */
    public function fetchForPaginator(int $page, int $itemPerPage, ?string $searchValue = null, ?array $filterBy = array(), ?array $orderBy = array(), ?array $columnsForOrder = array()): Paginator{
        $queryBuilder = $this->createQueryBuilder('c');

        # wyszukiwarka
        if(!is_null($searchValue) && $searchValue != ''){
            $searchValue = trim($searchValue);
            $queryBuilder->andWhere('c.category_name LIKE :searchTerm')->setParameter('searchTerm', '%'.$searchValue.'%');
        }

        # sortowanie
        if(!is_null($orderBy) && !empty($orderBy)){
            $chooseColumn = $columnsForOrder[$orderBy[0]['column']];
            $queryBuilder->orderBy($chooseColumn['name'], $orderBy[0]['dir']);
        }

        $paginator = new Paginator($queryBuilder->getQuery());
        $paginator
            ->getQuery()
            ->setFirstResult($itemPerPage * ($page-1))
            ->setMaxResults($itemPerPage);

        return $paginator;
    }// end fetchForPaginator

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
