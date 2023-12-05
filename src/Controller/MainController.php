<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(TrickRepository $trickRepository, Request $request): Response
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        $limit = $request->query->getInt('l', 15);

        if ($limit < 15) {
            $limit = 15;
        }

        $tricks = $trickRepository->findAllTricksWithLimit($limit);

        return $this->render('main/index.html.twig', [
            'tricks' => $tricks,
            'isAdmin' => $isAdmin
        ]);
    }

    #[Route('/mentions-legales', name: 'legal_notice')]
    public function legalNotice(): Response
    {
        return $this->render('main/legal_notice.html.twig');
    }
}
