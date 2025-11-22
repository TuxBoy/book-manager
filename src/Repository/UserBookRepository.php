<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserBook;
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
}
