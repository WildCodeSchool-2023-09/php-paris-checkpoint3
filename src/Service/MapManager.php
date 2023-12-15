<?php

namespace App\Service;

use App\Repository\TileRepository;
use App\Entity\Boat;

class MapManager
{

    private $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }
    public function tileExists($x, $y): bool
    {
        $tile = $this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]);

        return $tile !== null;
    }

    public function getRandomIsland()
    {
        $islandTiles = $this->tileRepository->findBy(['type' => 'island']);
        $randomTile = array_rand($islandTiles, 1);

        return $islandTiles[$randomTile];
    }

    public function checkTreasure(Boat $boat): bool
    {
        $tile = $this->tileRepository->findOneBy(['coordX' => $boat->getCoordX(), 'coordY' => $boat->getCoordY()]);

        return $tile->isHasTreasure();
    }
}