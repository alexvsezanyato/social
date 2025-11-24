<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function findWithPagination(int $offset, int $limit, ?int $authorId = null): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('p.id', 'DESC');

        if ($authorId !== null) {
            $queryBuilder->where('p.authorId = :authorId');
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
