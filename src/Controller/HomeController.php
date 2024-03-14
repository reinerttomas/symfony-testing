<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\DinosaurRepository;
use App\Service\HealthReport\HealthReportGetter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DinosaurRepository $dinosaurRepository, HealthReportGetter $healthReportGetter): Response
    {
        $dinosaurs = $dinosaurRepository->findAll();

        foreach ($dinosaurs as $dinosaur) {
            $dinosaur->setHealth($healthReportGetter->getHealthReport($dinosaur->getName()));
        }

        return $this->render('home/index.html.twig', [
            'dinosaurs' => $dinosaurs,
        ]);
    }
}
