<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\TricksImages;
use App\Entity\TricksVideos;
use App\Form\Tricks\TricksFormType;
use App\Form\Tricks\TricksVideosFormType;
use App\Form\Tricks\TricksImagesFormType;
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
        $videos = new TricksVideos;
        $images = new TricksImages;
        $items = ['tricks' => $tricks, 'videos' => $videos, 'images' => $images];

        $form = $this->createFormBuilder($items)
            ->add('Tricks', TricksFormType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('Images', TricksImagesFormType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('Videos', TricksVideosFormType::class, [
                'label' => false,
                'required' => false
            ])
            ->getForm();
 
        return $this->render('tricks/create.html.twig', [
            'controller_name' => 'TricksCreate',
            'createTricksForm' => $form->createView()
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
