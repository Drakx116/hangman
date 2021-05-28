<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/game/{id}", name="app_game_show")
     */
    public function show(int $id): Response
    {
        return $this->render('game/show.html.twig', [
            'game' => $id
        ]);
    }
}
