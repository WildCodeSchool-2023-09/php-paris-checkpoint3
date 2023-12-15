<?php

namespace App\Service;
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
        $tile = $this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]);

        if ($tile === null) {
            return false;
        } else {
            return true;
        }
    }

    public function getRandomIsland(array $tiles)
    {
        $tiles = $this->tileRepository->findAll();

        foreach($tiles as $key => $type) {
            if ($type === 'island') {
                return array_rand($tiles, 1);
            }
        }
    }
}