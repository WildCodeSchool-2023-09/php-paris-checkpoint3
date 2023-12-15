<?php

namespace App\Controller;

use App\Repository\BoatRepository;
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

    #[Route('/direction/{direction<[NSEW]>}', name: 'moveDirection')]
    public function moveDirection(string $direction,
    BoatRepository $boatRepository,
    MapManager $mapManager,
    EntityManagerInterface $entityManager): Response
    {
        $boat = $boatRepository->findOneBy([]);
        $newX = $boat->getCoordX();
        $newY = $boat->getCoordY();
    
        if ($direction === 'N') {
            $newY -= 1;
        } elseif ($direction === 'S') {
            $newY += 1;
        } elseif ($direction === 'E') {
            $newX += 1;
        } else {
            $newX -= 1;
        }
    
        if ($mapManager->tileExists($newX, $newY)) {
            $boat->setCoordX($newX);
            $boat->setCoordY($newY);
        } else {
            $this->addFlash('warning', 'Hop hop hop ! On essaie pas de sortir de la carte !');
        }
    
        $entityManager->flush();
    
        if ($mapManager->checkTreasure($boat) === true) {
            $this->addFlash('success', 'Tu as trouvé le trésor !');
        }
    
        return $this->redirectToRoute('map');
    }
}
