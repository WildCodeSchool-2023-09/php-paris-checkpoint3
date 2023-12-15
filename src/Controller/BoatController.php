<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;
use App\Service\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/boat')]
class BoatController extends AbstractController
{
    #[Route('/move/{x<\d+>}/{y<\d+>}', name: 'moveBoat')]
    public function moveBoat(
        int $x,
        int $y,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $boat = $boatRepository->findOneBy([]);
        
        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $entityManager->flush();
        
        return $this->redirectToRoute('map');
    }

    #[Route('/{direction<[NSEW]>}', name: 'moveDirection')]
    public function moveDirection(string $direction, BoatRepository $boatRepository, EntityManagerInterface $entityManager, MapManager $mapManager, TileRepository $tileRepository): Response 
    {
        $boat = $boatRepository->findOneBy([]);

        if ($direction === 'N' && $mapManager->tileExists($boat->getCoordX(), $boat->getCoordY() -1)) {
            $boat->setCoordY($boat->getCoordY() -1);
        } elseif ($direction === 'S' && $mapManager->tileExists($boat->getCoordX(), $boat->getCoordY() +1)) {
            $boat->setCoordY($boat->getCoordY() +1);
        } elseif ($direction === 'E' && $mapManager->tileExists($boat->getCoordX() +1, $boat->getCoordY())) {
            $boat->setCoordX($boat->getCoordX() +1);
        } elseif ($direction === 'W' && $mapManager->tileExists($boat->getCoordX() -1, $boat->getCoordY())) {
            $boat->setCoordX($boat->getCoordX() -1);
        } else {
            $this->addFlash('danger', 'You are out of the world');
        }
        
        $entityManager->flush();

        return $this->redirectToRoute('map');
    }
}
