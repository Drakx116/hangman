<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class BaseRepository extends ServiceEntityRepository
{
    /**
     * @param $entity
     * @throws ORMException
     */
    public function persist($entity): void
    {
        $this->_em->persist($entity);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function flush(): void
    {
        $this->_em->flush();
    }
}
