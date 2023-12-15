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
            if($mapManager->tileExists($boat->getCoordX(), $boat->getCoordY() + 1)){
                $boat->setCoordX($boat->getCoordX());
                $boat->setCoordY($boat->getCoordY() + 1);
            }
            else {
                $this->addFlash('warning', 'Hop hop hop ! On essaie pas de sortir de la carte !');
            }
        }
        else if ($direction === 'E') {
            if($mapManager->tileExists($boat->getCoordX() + 1, $boat->getCoordY())){
                $boat->setCoordX($boat->getCoordX() + 1);
                $boat->setCoordY($boat->getCoordY());
            }
            else {
                $this->addFlash('warning', 'Hop hop hop ! On essaie pas de sortir de la carte !');

            }
        }
        else {
            if($mapManager->tileExists($boat->getCoordX() - 1, $boat->getCoordY())){
                $boat->setCoordX($boat->getCoordX() - 1);
                $boat->setCoordY($boat->getCoordY());
            }
            else {
                $this->addFlash('warning', 'Hop hop hop ! On essaie pas de sortir de la carte !');
            }
        }

        $entityManager->flush();

        if ($mapManager->checkTreasure($boat) === true) {
            $this->addFlash('success', 'Tu as trouvé le trésor !');
        }

        return $this->redirectToRoute('map');
    }
}
