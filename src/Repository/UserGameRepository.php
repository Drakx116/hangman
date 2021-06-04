<?php

namespace App\Repository;

use App\Entity\UserGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGame[]    findAll()
 * @method UserGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGame::class);
    }

    /**
     * @param UserGame $userGame
     * @throws ORMException
     */
    public function persist(UserGame $userGame): void
    {
        $this->_em->persist($userGame);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function flush(): void
    {
        $this->_em->flush();
    }
}
