<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(TricksRepository $tricksRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'tricks' => $tricksRepository->findAll([], [
                'uptated_at' => 'asc',
                'created_at' => 'asc'
            ])
        ]);
    }
}
