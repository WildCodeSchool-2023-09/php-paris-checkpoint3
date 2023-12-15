<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use Doctrine\Migrations\Version\Direction;
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
        
        return $this->redirectToRoute('map', $boat);
    }

    #[Route('/{direction<[NSEW]>}', name: 'directionBoat')]
    public function moveDirection(string $direction, BoatRepository $boatRepository, EntityManagerInterface $entityManager): Response {

        $boat = $boatRepository->findOneBy([]);
        
        $boatY = $boat->getCoordY();
        $boatX = $boat->getCoordX();

        switch ($direction) {
            case 'N':
                $boat->setCoordY($boatY-1);
                break;
            case 'S':
                $boat->setCoordY($boatY+1);
                break;
            case 'E':
                $boat->setCoordX($boatX+1);
                break;
            case 'W':
                $boat->setCoordX($boatX-1);
                break;
            default:
                $this->addFlash('error', 'Jack is missing');
        }

        $entityManager->flush();

        return $this->redirectToRoute('map');

    }
}
