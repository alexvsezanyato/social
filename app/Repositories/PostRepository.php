<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function findWithPagination(
        ?int $authorId = null,
        ?int $limit = null,
        ?int $before = null,
    ): array {
        $queryBuilder = $this->createQueryBuilder('p')
            ->setMaxResults($limit ?? 1)
            ->orderBy('p.id', 'DESC');

        if ($before !== null) {
            $queryBuilder->andWhere('p.id < :before');
            $queryBuilder->setParameter('before', $before);
        }

        if ($authorId !== null) {
            $queryBuilder->andWhere('p.authorId = :authorId');
            $queryBuilder->setParameter('authorId', $authorId);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findRecommended(int $limit = 1, int $from = 0): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($from)
            ->getQuery()
            ->getResult();
    }
}
