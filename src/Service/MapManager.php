<?php

namespace App\Service;
use App\Repository\TileRepository;
class MapManager 
{
    public function tileExists(int $x, int $y, TileRepository $tileRepository): bool
    {   
        return $x >= 0 && $y >= 0;
    }
}