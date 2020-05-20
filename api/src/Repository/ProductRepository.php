<?php


namespace App\Repository;


use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;

class ProductRepository extends ServiceEntityRepository
{
    // we can get it from config
    const ITEMS_PER_PAGE = 20;


    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Product::class);
    }

    public function getAllProducts(int $page = 1)
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $paginator = $this->paginator($page, $queryBuilder);

        return $paginator;
    }

    /**
     * @return array|int|string
     */
    public function getAllOrders()
    {
        return $this->createQueryBuilder('p')
            ->addOrderBy('p.price')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param int $itemsMax
     * @return array|int|string
     */
    public function getMaximumCheapOrders(int $itemsMax)
    {
        return $this->createQueryBuilder('p')
            ->addOrderBy('p.price')
            ->setMaxResults($itemsMax)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param int $page
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @return Paginator
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function paginator(int $page, \Doctrine\ORM\QueryBuilder $queryBuilder): Paginator
    {
        $firstResult = ($page - 1) * self::ITEMS_PER_PAGE;

        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE);
        $queryBuilder->addCriteria($criteria);

        $doctrinePaginator = new DoctrinePaginator($queryBuilder);
        $paginator = new Paginator($doctrinePaginator);
        return $paginator;
    }
}
