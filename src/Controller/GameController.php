<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\UserGame;
use App\Repository\GameRepository;
use App\Repository\UserGameRepository;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AlphabetType;

class GameController extends AbstractController
{
    /**
     * @Route("/game/list", name="app_game_list")
     */
    public function list(GameRepository $repository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/game/{token}", name="app_game_show")
     * @ParamConverter("game_token_converter")
     * @throws ORMException
     */
    public function show(Request $request, Game $game, UserGameRepository $userGameRepository): Response
    {
        $userGame = $request->attributes->get('user_game');

        $form = $this->createForm(AlphabetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $letter = $form->get('letter')->getData();
            [ 'word' => $word, 'validations' => $validations ] = $this->revealFoundLetter($letter, $userGame->getWord());

            // * Decrease attempts if the user's letter is not in the word, update the user game's word otherwise
            ($validations) ? $userGame->setWord($word) : $userGame->decreaseAttempts();

            $userGameRepository->flush();
        }

        if ($userGame->getAttempts() < 1) {
            if ($userGame->getFailed() === null) {
                $userGame->setSuccess(false);
                $userGame->setFailed(true);
                $userGameRepository->flush();
            }

            return $this->redirectToRoute('app_game_list');
        }

        if ($this->hasWon($userGame->getWord())) {
            $userGame->setSuccess(true);
            $userGame->setFailed(false);
            $userGameRepository->flush();

            return $this->redirectToRoute('app_game_list');
        }

        return $this->render('game/show.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
            'userGame' => $userGame
        ]);
    }

    /**
     * @param string $letter
     * @param array  $word
     * @return array
     */
    private function revealFoundLetter(string $letter, array $word): array
    {
        $validationCount = 0;

        foreach ($word as $i => $item) {
            if ($item['letter'] === strtolower($letter)) {
                $word[$i]['found'] = true;
                $validationCount++;
            }
        }

        return [
            'word' => $word,
            'completed' => $this->hasWon($word),
            'validations' => $validationCount
        ];
    }

    private function hasWon(array $word): bool
    {
        $foundLetters = 0;
        foreach ($word as $item) {
            if ($item['found']) {
                $foundLetters++;
            }
        }

        return $foundLetters === count($word);
    }
}
