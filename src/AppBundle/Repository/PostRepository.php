<?php

namespace AppBundle\Repository;

use AppBundle\Repository\AbstractGenericRepository;
use AppBundle\Repository\Interfaces\PostRepositoryInterface;

class PostRepository extends AbstractGenericRepository implements PostRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function getResultFilterCount($motcle)
    {
        $qb = $this->getQueryResultFilter($motcle);
        $qb->select('COUNT(a.id)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @inheritdoc
     */
    public function getResultFilterPaginated($motcle, $limit = 20, $offset = 0)
    {
        $limit = (int) $limit;
        if ($limit <= 0) {
            throw new \LogicException('$limit must be greater than 0.');
        }

        $qb = $this->getQueryResultFilter($motcle);

        $qb->orderBy('a.name', 'ASC');

        $qb->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function getQueryResultFilter($motcle)
    {
        $qb = $this->getBuilder('a');
        $qb
            ->where("a.name LIKE :motcle")
            ->orderBy('a.name', 'ASC')
            ->setParameter('motcle', '%' . $motcle . '%');

        return $qb;
    }
}
