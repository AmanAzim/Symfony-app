<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\MicroPost;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<MicroPost>
 *
 * @method MicroPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method MicroPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method MicroPost[]    findAll()
 * @method MicroPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicroPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }

    public function save(MicroPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush(); // when we want to save the data to the db after finishing every modification
        }
    }

    public function remove(MicroPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllWithComments(): Array
    {
        return $this->getFindAllQuery(true)
            ->getQuery()
            ->getResult();
    }

    public function findAllByAuthor(int | User $author): Array
    {
        return $this->getFindAllQuery(withComments: true, withLikes: true, withAuthor: true, withProfile: true)
            ->where('microPost.author = :author')
            ->setParameter('author', $author instanceof User ? $author->getId() : $author)
            ->getQuery()
            ->getResult();
    }

    public function findAllWithMinLikes(int $minLikes): Array
    {
        $idList = $this->getFindAllQuery(withLikes: true)
            ->select('microPost.id')
            ->groupBy('microPost.id')
            ->having('COUNT(microPostLikes) >= :minLikes')
            ->setParameter('minLikes', $minLikes)
            ->getQuery()
            ->getSingleColumnResult();

        return $this->getFindAllQuery(withComments: true, withLikes: true, withAuthor: true, withProfile: true)
            ->where('microPost.id in (:idList)')
            ->setParameter('idList',  $idList)
            ->getQuery()
            ->getResult();
    }

    public function findAllByAuthors(Collection | array $authors): Array
    {
        return $this->getFindAllQuery(withComments: true, withLikes: true, withAuthor: true, withProfile: true)
            ->where('microPost.author in (:authors)')
            ->setParameter('authors', $authors)
            ->getQuery()
            ->getResult();
    }


    public function getFindAllQuery(bool $withComments = false, bool $withLikes = false, bool $withAuthor = false, bool $withProfile = false): QueryBuilder
    {
        $query = $this->createQueryBuilder('microPost');

        if ($withComments) {
            $query->leftJoin('microPost.comments', 'microPostComments')
            ->addSelect('microPostComments');
        }

        if ($withLikes) {
            $query->leftJoin('microPost.likedBy', 'microPostLikes')
            ->addSelect('microPostLikes');
        }

        if ($withAuthor || $withProfile) {
            $query->leftJoin('microPost.author', 'microPostAuthor')
            ->addSelect('microPostAuthor');
        }

        if ($withProfile) {
            $query->leftJoin('microPostAuthor.userProfile', 'authorProfile')
            ->addSelect('authorProfile');
        }

        return $query->orderBy('microPost.createdAt', 'DESC');
    }

//    /**
//     * @return MicroPost[] Returns an array of MicroPost objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MicroPost
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
