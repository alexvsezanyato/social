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

    public function findRecommended(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }
}
