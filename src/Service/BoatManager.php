<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;
use App\Service\MapManager;

class BoatManager
{
    private $boatRepository;

    public function __construct(BoatRepository $boatRepository)
    {
        $this->boatRepository = $boatRepository;
    }

    public function move($direction)
    {
        $boat = $this->boatRepository->findOneBy([]);

    if ($direction === 'N') {
        if($mapManager->tileExists($boat->getCoordX(), $boat->getCoordY() - 1)){
            $boat->setCoordX($boat->getCoordX());
            $boat->setCoordY($boat->getCoordY() - 1);
        }
        else {
            $this->addFlash('warning', 'Hop hop hop ! On essaie pas de sortir de la carte !');
        }
    }
    else if ($direction === 'S') {
        if ($mapManager->tileExists($boat->getCoordX(), $boat->getCoordY() + 1)) {
            $boat->setCoordX($boat->getCoordX());
            $boat->setCoordY($boat->getCoordY() + 1);
        }
        else {
            $this->addFlash('warning', 'Hop hop hop ! On essaie pas de sortir de la carte !');
        }
    }
    else if ($direction === 'E') {
        if ($mapManager->tileExists($boat->getCoordX() + 1, $boat->getCoordY())) {
            $boat->setCoordX($boat->getCoordX() + 1);
            $boat->setCoordY($boat->getCoordY());
        }
        else {
            $this->addFlash('warning', 'Hop hop hop ! On essaie pas de sortir de la carte !');

        }
    }
    else {
        if ($mapManager->tileExists($boat->getCoordX() - 1, $boat->getCoordY())) {
            $boat->setCoordX($boat->getCoordX() - 1);
            $boat->setCoordY($boat->getCoordY());
        }
        else {
            $this->addFlash('warning', 'Hop hop hop ! On essaie pas de sortir de la carte !');
        }
    }
    }
}