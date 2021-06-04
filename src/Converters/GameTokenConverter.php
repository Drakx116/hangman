<?php

namespace App\Converters;

use App\Entity\User;
use App\Entity\UserGame;
use App\Repository\GameRepository;
use App\Repository\UserGameRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 * Class GameTokenConverter
 * @package App\Converters
 */
class GameTokenConverter implements ParamConverterInterface
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var UserGameRepository
     */
    private $userGameRepository;

    /**
     * @var Security
     */
    private $security;

    /**
     * GameTokenConverter constructor.
     */
    public function __construct(GameRepository $gameRepository, UserGameRepository $userGameRepository, Security $security)
    {
        $this->gameRepository = $gameRepository;
        $this->security = $security;
        $this->userGameRepository = $userGameRepository;
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        if (!$token = $request->get('token')) {
            return false;
        }

        if (!$game = $this->gameRepository->findOneBy([ 'token' => $token ])) {
            return false;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        // * Find the pending user game if exists, create a new one otherwise
        $userGame = $this->userGameRepository->findOnePendingUserGame($user, $game);

        if (!$userGame) {
            $userGame = new UserGame();
            $userGame->setAttempts($game->getAttempts());
            $userGame->setGame($game);
            $userGame->setUser($user);
            $userGame->setWord($this->explodeSecret($game->getSecret()));

            $this->userGameRepository->persist($userGame);
            $this->userGameRepository->flush();
        }

        $request->attributes->set('game', $game);
        $request->attributes->set('user_game', $userGame);

        return true;
    }

    /**
     * @param ParamConverter $configuration
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getName() === 'game_token_converter';
    }

    private function explodeSecret(string $secret): array
    {
        $word = [];
        for ($i=0, $max = strlen($secret); $i < $max; $i++) {
            $word[] = [ 'letter' => $secret[$i], 'found' => false ];
        }

        return $word;
    }
}
