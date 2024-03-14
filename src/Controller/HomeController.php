<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\DinosaurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DinosaurRepository $dinosaurRepository): Response
    {
        $dinosaurs = $dinosaurRepository->findAll();

        return $this->render('home/index.html.twig', [
            'dinosaurs' => $dinosaurs,
        ]);
    }
}
