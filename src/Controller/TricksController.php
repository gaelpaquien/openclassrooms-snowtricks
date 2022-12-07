<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Form\TricksCreateFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tricks', name: 'tricks_')]
class TricksController extends AbstractController
{
    #[Route('/creation', name: 'create')]
    public function create(Request $request): Response
    {
        $tricks = new Tricks;
        $form = $this->createForm(TricksCreateFormType::class, $tricks);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('main');
        }

        return $this->render('tricks/create.html.twig', [
            'controller_name' => 'TricksCreate',
            'createForm' => $form->createView()
        ]);
    }

    #[Route('/{slug}/{id}', name: 'details')]
    public function details(Tricks $tricks): Response
    {
        return $this->render('tricks/details.html.twig', [
            'controller_name' => 'TricksDetails',
        ]);
    }

    #[Route('/{slug}/{id}/edition', name: 'update')]
    public function update(Tricks $tricks): Response
    {
        return $this->render('tricks/update.html.twig', [
            'controller_name' => 'TricksUpdate',
        ]);
    }

    #[Route('/{slug}/{id}/suppression', name: 'delete')]
    public function delete(Tricks $tricks): void
    {}
}
