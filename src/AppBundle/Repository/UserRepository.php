<?php

namespace AppBundle\Repository;

use Swe\CoreBundle\Repository\AbstractGenericRepository;
use AppBundle\Repository\Interfaces\UserRepositoryInterface;

class UserRepository extends AbstractGenericRepository implements UserRepositoryInterface {

    /**
     * @inheritdoc
     */
    public function getResultFilterCount($motcle) {
        $qb = $this->getQueryResultFilter($motcle);
        $qb->select('COUNT(a.id)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @inheritdoc
     */
    public function getResultFilterPaginated($motcle, $limit = 20, $offset = 0) {
        $limit = (int) $limit;
        if ($limit <= 0) {
            throw new \LogicException('$limit must be greater than 0.');
        }

        $qb = $this->getQueryResultFilter($motcle);

        $qb->orderBy('a.nom', 'ASC');

        $qb->setFirstResult($offset)
                ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function getQueryResultFilter($motcle) {
        $qb = $this->getBuilder('a');
        $qb
                ->where("a.nom LIKE :motcle")
                ->orderBy('a.nom', 'ASC')
                ->setParameter('motcle', '%' . $motcle . '%');

        return $qb;
    }

}
