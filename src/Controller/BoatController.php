<?php

namespace App\Controller;

use App\Service\MapManager;
use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    public function moveDirection(
        string $direction, 
        BoatRepository $boatRepository, 
        EntityManagerInterface $em,
        MapManager $mapManager
        ): Response {
        $boat = $boatRepository->findOneBy([]);

        $x = $boat->getCoordX();
        $y = $boat->getCoordY();
        switch($direction) {
            case 'N' :
                $boat->setCoordY($y - 1);
                break;
            case 'S' :
                $boat->setCoordY($y + 1);
                break;
            case 'E' :
                $boat->setCoordY($x - 1);
                break;
            case 'W' :
                $boat->setCoordY($x + 1);
                break;
            default :
        }

        if($mapManager->tileExists($boat->getCoordX(), $boat->getCoordY())) {
            $em->persist($boat);
            $em->flush(); 
        } else {
            $this->addFlash('TU VAS OU CHAKAL', 'REEESTE ICI !');
        }

        return $this->redirectToRoute('map');
        }
}