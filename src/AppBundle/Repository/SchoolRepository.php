<?php

namespace AppBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * SchoolRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SchoolRepository extends BaseRepository
{

    
    public function filter($term = null, $orderBy = [], $limit = 10, $offset = 0, \AppBundle\Entity\User $user = null)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->setMaxResults($limit)
                ->setFirstResult($offset);

        if (!is_null($term) && strlen(trim($term)) > 0) {
            $qb->where($qb->expr()->orX($qb->expr()->like('s.name', '?1'), $qb->expr()->like('s.legalName', '?1')))
                    ->setParameter(1, "%{$term}%");
        }

        foreach ($orderBy as $key => $val) {
            $qb->addOrderBy($key, $val);
        }
        if (!is_null($user)) {
            $qb->andWhere('s.owner = ?2')
                    ->setParameter(2, $user);
        }
        
        return new ArrayCollection($qb->getQuery()->getResult());
    }

}