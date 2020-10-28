<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    private $pageSize = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return array
     */

    public function findLatest($page = 1)
    {
        $query = $this->createQueryBuilder('p')
            ->addSelect('a', 't')
            ->innerJoin('p.author', 'a')
            ->leftJoin('p.tags', 't')
            ->where('p.publishedAt <= :now')
            ->orderBy('p.publishedAt', 'DESC')
            ->setParameter('now', new \DateTime())
            ->getQuery();

        $paginator = new Paginator($query);
        $totalItems = $paginator->count();

        $pagesCount = ceil($totalItems / $this->pageSize);

        $paginator
            ->getQuery()
            ->setFirstResult($this->pageSize * ($page - 1))
            ->setMaxResults($this->pageSize);

        return [
            'totalItems' => $totalItems,
            'pagesCount' => $pagesCount,
            'posts' => $paginator->getIterator(),
        ];
    }


    public function findByID($id): ?Post
    {
        return $this->createQueryBuilder('p')
            ->addSelect('a','t')
            ->innerJoin('p.author','a')
            ->leftJoin('p.tags','t')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
