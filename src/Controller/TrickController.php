<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CreateCommentFormType;
use App\Form\Trick\TrickFormType;
use App\Repository\CommentRepository;
use App\Repository\TrickImageRepository;
use App\Repository\TrickVideoRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/trick', name: 'trick_')]
class TrickController extends AbstractController
{
    #[Route('/creation', name: 'create')]
    public function create(
        Request $request, 
        EntityManagerInterface $em,
        TextService $text): Response
    {
        // Check if user is logged
        if (!$this->getUser()) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('main');
        }

        // Form to create a trick
        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if trick title already exist
            $trickExist = $em->getRepository(Trick::class)->findOneBy(['title' => $trick->getTitle()]);
            if ($trickExist) {
                $this->addFlash('danger', "Le trick '{$trick->getTitle()}' existe déjà");
                return $this->redirectToRoute('trick_create');
            }

            // Add trick data
            $trick->setAuthor($this->getUser());
            $trick->setSlug($text->slugify($trick->getTitle()));
            $trick->setCreatedAt(new \DateTimeImmutable());
            $trick->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($trick);

            // Save and redirect
            $em->flush();
            $this->addFlash('success', "Le trick '{$trick->getTitle()}' a été crée avec succès");
            return $this->redirectToRoute('main');
        }
 
        return $this->render('trick/create.html.twig', [
            'controller_name' => 'TrickCreate',
            'createTrickForm' => $form->createView()
        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(
        Trick $trick,
        CommentRepository $commentRepository,
        Request $request,
        EntityManagerInterface $em): Response
    {
        // Check if user is admin
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        // Get the current page
        $page = $request->query->getInt('p', 1);
        if ($page < 1) {
            $page = 1;
        }

        // Get all comments with pagination
        $comments = $commentRepository->findAllCommentsPaginated($trick->getId(), $page, 10);

        // Form to create a comment
        $comment = new Comment;
        $form = $this->createForm(CreateCommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Add comment data
            $comment = $form->getData();
            $comment->setTrick($trick);
            $comment->setAuthor($this->getUser());
            $em->persist($comment);

            // Save and redirect
            $em->flush();
            $this->addFlash('success', 'Commentaire ajouté avec succès');
            return $this->redirectToRoute('trick_details', [
                'slug' => $trick->getSlug(),
                'isAdmin' => $isAdmin
            ]);
        }

        return $this->render('trick/details.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'createCommentForm' => $form->createView(),
            'isAdmin' => $isAdmin
        ]);
    }

    #[Route('/{slug}/edition', name: 'update')]
    public function update(
        Trick $trick, 
        Request $request,
        EntityManagerInterface $em): Response
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        // Check if user is author of the trick or if user is admin
        if ($trick->getAuthor() !== $this->getUser() && !$isAdmin) {
            $this->addFlash('danger', 'Vous ne pouvez pas accéder à cette page');
            return $this->redirectToRoute('main');
        }

        // Form to update a trick
        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if trick already exist with the new title
            $trickExist = $em->getRepository(Trick::class)->findOneBy(['title' => $trick->getTitle()]);
            if ($trickExist && $trickExist->getId() !== $trick->getId()) {
                $this->addFlash('danger', "Le trick '{$trick->getTitle()}' existe déjà");
                return $this->redirectToRoute('trick_update', ['slug' => $trick->getSlug()]);
            }

            // Update trick data
            $trick->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($trick);

            // Save and redirect
            $em->flush();
            $this->addFlash('success', "Le trick '{$trick->getTitle()}' a été modifié avec succès");
            return $this->redirectToRoute('trick_details', [
                'slug' => $trick->getSlug(),
                'isAdmin' => $isAdmin
            ]);
        }

        return $this->render('trick/update.html.twig', [
            'trick' => $trick,
            'updateTrickForm' => $form->createView(),
            'isAdmin' => $isAdmin
        ]);
    }

    #[Route('/{slug}/suppression', name: 'delete')]
    public function delete(
        Trick $trick, 
        EntityManagerInterface $em, 
        TrickVideoRepository $trickVideo,
        TrickImageRepository $trickImage): Response
    {
        // Check if user is admin
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        // Check if user is author of the trick and if user is admin
        if ($trick->getAuthor() !== $this->getUser() && !$isAdmin) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer ce trick');
            return $this->redirectToRoute('trick_details', [
                'slug' => $trick->getSlug(), 
                'isAdmin' => $isAdmin]);
        }

        // Delete comments of the trick
        $comments = $trick->getComment();
        foreach ($comments as $comment) {
            $em->remove($comment);
        }

        // Delete videos of the trick
        $videos = $trickVideo->findByTrick($trick->getId());
        foreach ($videos as $video) {
            $em->remove($video);
        }

        // Delete images of the trick
        $images = $trickImage->findByTrick($trick->getId());
        foreach ($images as $image) {
            $em->remove($image);
        }

        // Delete trick
        $em->remove($trick);

        // Save and redirect
        $em->flush();
        $this->addFlash('success', "Le trick '{$trick->getTitle()}' a été supprimé avec succès");
        return $this->redirectToRoute('main', ['isAdmin' => $isAdmin]);
    }

    #[Route('/{slug}/commentaire/{id}/suppression', name: 'delete_comment')]
    public function deleteComment(Comment $comment, EntityManagerInterface $em): Response
    {
        // Check if user is admin
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        // Check if user is author of the comment and if user is admin
        if ($comment->getAuthor() !== $this->getUser() && !$isAdmin) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer ce commentaire');
            return $this->redirectToRoute('trick_details', [
                'slug' => $comment->getTrick()->getSlug(),
                'isAdmin' => $isAdmin
            ]);
        }

        // Delete comment
        $em->remove($comment);

        // Save and redirect
        $em->flush();
        $this->addFlash('success', 'Le commentaire a été supprimé avec succès');
        return $this->redirectToRoute('trick_details', [
            'slug' => $comment->getTrick()->getSlug(),
            'isAdmin' => $isAdmin
        ]);
    }
}
