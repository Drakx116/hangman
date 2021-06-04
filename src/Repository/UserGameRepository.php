<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\User;
use App\Entity\UserGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

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

    /**
     * @throws NonUniqueResultException
     */
    public function findOnePendingUserGame(User $user, Game $game): ?UserGame
    {
        $qb = $this->createQueryBuilder('user_game');

        return $qb->andWhere('user_game.user = :user')
            ->andWhere('user_game.game = :game')
            ->andWhere($qb->expr()->gt('user_game.attempts', 0))
            ->andWhere($qb->expr()->isNull('user_game.success'))
            ->andWhere($qb->expr()->isNull('user_game.failed'))
            ->setParameters([ 'user' => $user, 'game' => $game ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
