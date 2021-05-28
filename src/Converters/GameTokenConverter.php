<?php

namespace App\Converters;

use App\Repository\GameRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * GameTokenConverter constructor.
     */
    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     * @return bool
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        if (!$token = $request->get('token')) {
            return false;
        }

        if (!$game = $this->gameRepository->findOneBy([ 'token' => $token ])) {
            return false;
        }

        $request->attributes->set('game', $game);

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
}
