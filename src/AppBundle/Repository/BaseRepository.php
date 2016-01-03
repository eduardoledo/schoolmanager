<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Description of BaseRepository
 *
 * @author Eduardo Ledo <eduardo.ledo@gmail.com>
 */
class BaseRepository extends EntityRepository
{

    /**
     * @return integer
     */
    public function count()
    {
        return $this->createQueryBuilder('obj')
                        ->select('count(obj.id)')
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    /**
     * @return null|\DateTime
     */
    public function findLastModified()
    {
        $query = $this->createQueryBuilder('obj')
                ->select('obj.updated')
                ->orderBy('obj.updated', 'desc')
                ->setMaxResults(1);
        $result = $query->getQuery()->getOneOrNullResult();

        return null == $result ? null : $result['updated'];
    }

}