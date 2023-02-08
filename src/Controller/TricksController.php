<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Tricks;
use App\Entity\TricksImages;
use App\Entity\TricksVideos;
use App\Form\CreateCommentFormType;
use App\Form\Tricks\TricksFormType;
use App\Form\Tricks\TricksVideosFormType;
use App\Form\Tricks\TricksImagesFormType;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/{slug}', name: 'details')]
    public function details(
        Tricks $tricks,
        CommentsRepository $commentsRepository,
        Request $request,
        EntityManagerInterface $em): Response
    {
        $comment = new Comments;

        $form = $this->createForm(CreateCommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setTrick($tricks);
            $comment->setAuthor($this->getUser());
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire ajouté avec succès');
            return $this->redirectToRoute('tricks_details', ['slug' => $tricks->getSlug()]);
        }

        // Get the current page
        $page = $request->query->getInt('p', 1);

        // Get the comments with pagination
        $comments = $commentsRepository->findCommentsPaginated($tricks->getId(), $page, 10);

        return $this->render('tricks/details.html.twig', [
            'tricks' => $tricks,
            'comments' => $comments,
            'createCommentForm' => $form->createView()
        ]);
    }

    #[Route('/{slug}/edition', name: 'update')]
    public function update(Tricks $tricks, Request $request): Response
    {
        $form = $this->createForm(TricksFormType::class, $tricks);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        }

        return $this->render('tricks/update.html.twig', [
            'tricks' => $tricks,
            'updateTricksForm' => $form->createView()
        ]);
    }

    #[Route('/{slug}/suppression', name: 'delete')]
    public function delete(Tricks $tricks): void
    {}

    #[Route('/{slug}/commentaire/{id}/suppression', name: 'delete_comment')]
    public function deleteComment(Comments $comments, EntityManagerInterface $em): Response
    {
        // Check if user is author of the comment
        if ($comments->getAuthor() !== $this->getUser()) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer ce commentaire');
            return $this->redirectToRoute('tricks_details', ['slug' => $comments->getTrick()->getSlug()]);
        }

        // Delete comment
        $em->remove($comments);
        $em->flush();
        return $this->redirectToRoute('tricks_details', ['slug' => $comments->getTrick()->getSlug()]);
    }
}
