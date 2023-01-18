<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(TricksRepository $tricksRepository, Request $request): Response
    {
        $limit = $request->query->getInt('l', 15);

        if ($limit < 15) {
            $limit = 15;
        }

        $tricks = $tricksRepository->findTricksWithLimit($limit);

        //dd($tricks);

        return $this->render('main/index.html.twig', [
            'tricks' => $tricks
        ]);
    }
}
