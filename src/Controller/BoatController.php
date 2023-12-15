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
    public function moveDirection(
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager,
        MapManager $mapManager,
        string $direction
    ): Response {
        $boat = $boatRepository->findOneBy([]);

        $x = $boat->getCoordX();
        $y = $boat->getCoordY();

        $error = false;
        
        switch ($direction)
        {
            case 'N':
                if ($mapManager->tileExists($x, $y-1)) {
                    $boat->setCoordY($y-1);
                }
                else {
                    $error = true;
                }
                break;
            case 'S':
                if ($mapManager->tileExists($x, $y+1)) {
                    $boat->setCoordY($y+1);
                }
                else {
                    $error = true;
                }
                break;
            case 'E':
                if ($mapManager->tileExists($x+1, $y)) {
                    $boat->setCoordX($x+1);
                }
                else {
                    $error = true;
                }
                break;
            case 'W':
                if ($mapManager->tileExists($x-1, $y)) {
                    $boat->setCoordX($x-1);
                }
                else {
                    $error = true;
                }
                break;
            default:
                $this->addFlash('danger', 'Wrong direction!');
                break;
        }

        if ($error === true) {
            $this->addFlash('danger', 'You cannot sail outside the map!');
        }
        if ($mapManager->checkTreasure($boat)) {
            $this->addFlash('success', 'Congrats, you found the treasure!');
        }

        $entityManager->flush();
        return $this->redirectToRoute('map');
    }
}
