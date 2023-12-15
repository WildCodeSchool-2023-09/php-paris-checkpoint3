<?php

namespace App\Service;
use App\Entity\Boat;
use App\Entity\Tile;
use App\Repository\TileRepository;

class MapManager
{
    private TileRepository $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function tileExists(int $x, int $y): bool
    {
        if ($this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y])) {
            return true;
        } else {
            return false;
        }
    }

    public function getRandomIsland(): Tile
    {
        $arrayTile = array_merge($this->tileRepository->findBy(['type' => 'port']), $this->tileRepository->findBy(['type' => 'island']));
        return $arrayTile[array_rand($arrayTile)];
    }

    public function checkTreasure(Boat $boat): bool
    {
        $treasureTile = $this->tileRepository->findOneBy(['hasTreasure' => true]);

        if ($boat->getCoordX() === $treasureTile->getCoordX() && $boat->getCoordY() === $treasureTile->getCoordY()) {
            return true;
        } else {
            return false;
        }
    }
}
