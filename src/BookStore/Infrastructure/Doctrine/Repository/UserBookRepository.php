<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\Doctrine\Repository;

use App\BookStore\Domain\Model\UserBook;
use App\Security\Domain\Model\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends EntityRepository<UserBook>
 */
final class UserBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBook::class);
    }

    public function save(UserBook $userBook): void
    {
        $this->getEntityManager()->persist($userBook);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<UserBook>
     */
    public function allOfUser(User $user): array
    {
        return $this->createQueryBuilder('ub')
            ->addSelect('b')
            ->join('ub.book', 'b')
            ->andWhere('ub.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findById(int $id): ?UserBook
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
