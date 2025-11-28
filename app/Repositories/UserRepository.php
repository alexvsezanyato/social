<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;

/**
 * @inheritdoc
 */
class UserRepository extends EntityRepository
{
    public function findSuggestedFriends(int $userId)
    {
        $friendsDQL = $this->createQueryBuilder('u2')
            ->select('f.id')
            ->join('u2.friends', 'f')
            ->where('u2 = :user')
            ->getDQL();

        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->where($queryBuilder->expr()->notIn('u.id', $friendsDQL));
        $queryBuilder->andWhere('u != :user');
        $queryBuilder->setParameter('user', $this->find($userId));

        return $queryBuilder->getQuery()->getResult();
    }
}
