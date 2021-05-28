<?php

namespace App\DataFixtures;

use App\Entity\Game;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GameFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $game = new Game();
        $game->setSecret('webinar');
        $game->setAttempts(7);

        $manager->persist($game);
        $manager->flush();
    }
}
