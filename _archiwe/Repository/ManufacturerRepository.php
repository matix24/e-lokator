<?php

namespace App\Repository;

use App\Entity\Manufacturer;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Manufacturer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Manufacturer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Manufacturer[]    findAll()
 * @method Manufacturer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManufacturerRepository extends ServiceEntityRepository{
    
    public function __construct(ManagerRegistry $registry){
        parent::__construct($registry, Manufacturer::class);
    }

    /**
     * paginator listy dostawców
     * @param int $page nr strony
     * @param int $itemPerPage ile ma być pozycji na jednej stronie
     * @param null|string $searchValue wyszukiwana treść
     * @param null|array $filterBy filtrowanie tabeli
     * @param null|array $orderBy sortowanie tabeli
     * @param null|array $columnsForOrder kolumny sortowania
     * @return array tablica danych
     */
    public function fetchForPaginator(int $page, int $itemPerPage, ?string $searchValue = null, ?array $filterBy = array(), ?array $orderBy = array(), ?array $columnsForOrder = array()): Paginator{
        
        $queryBuilder = $this->createQueryBuilder('m');

        # wyszukiwarka
        if(!is_null($searchValue) && $searchValue != ''){
            $searchValue = trim($searchValue);
            $queryBuilder->andWhere('m.manufacturer_name LIKE :searchTerm')->setParameter('searchTerm', '%'.$searchValue.'%');
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


    /**
     * pobieram danego dostawcę po id
     * @param int $id id_manufacturer
     * @return \Manufacturer::class
     */
    public function findById(int $id): ?Manufacturer {
        return $this->createQueryBuilder('m')
            ->andWhere('m.id_manufacturer = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }// end findById




    // /**
    //  * @return Manufacturer[] Returns an array of Manufacturer objects
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
    public function findOneBySomeField($value): ?Manufacturer
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
