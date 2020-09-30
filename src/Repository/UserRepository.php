<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
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
        
        $queryBuilder = $this->createQueryBuilder('u');

        # wyszukiwarka
        if(!is_null($searchValue) && $searchValue != ''){
            $searchValue = trim($searchValue);
            $queryBuilder->andWhere('u.email LIKE :searchTerm')->setParameter('searchTerm', '%'.$searchValue.'%');
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
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
}// end class
