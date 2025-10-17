<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    //DQL method getnbrbooks
    public function getNbrBooksDQL(): int
    {
        $em=$this->getEntityManager();
        $dql="SELECT COUNT(b.id) FROM App\Entity\Book b";
        return (int) $em->createQuery($dql)->getSingleScalarResult();
    }

    //query builder method getnbrbooks
    public function getNbrBooksQB(): int
    {
        return (int) $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    //DQL method getbooksbyauthor
    public function getBooksByAuthorDQL(int $authorId): array{
        $em=$this->getEntityManager();
        $dql="SELECT b FROM App\Entity\Book b JOIN b.author_b a WHERE a.id = :authorId";
        return $em->createQuery($dql)
            ->setParameter('authorId',$authorId)
            ->getResult();
    }

    //query builder method getbooksbyauthor
    public function getBooksByAuthorQB(int $authorId): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.author_b','a')
            ->where('a.id = :authorId')
            ->setParameter('authorId',$authorId)
            ->getQuery()
            ->getResult();
    }
}
