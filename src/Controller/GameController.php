<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
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
     */
    public function show(Request $request, Game $game): Response
    {
        $form = $this->createForm(AlphabetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dd($form->getData());
        }

        return $this->render('game/show.html.twig', [
            'game' => $game,
            'form' => $form->createView()
        ]);
    }
}
