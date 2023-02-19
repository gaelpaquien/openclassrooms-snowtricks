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
use App\Repository\TricksImagesRepository;
use App\Repository\TricksVideosRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tricks', name: 'tricks_')]
class TricksController extends AbstractController
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

        $tricks = new Tricks;
        $videos = new TricksVideos;
        $images = new TricksImages;
        $items = ['tricks' => $tricks, 'videos' => $videos, 'images' => $images];

        $form = $this->createFormBuilder($items)
            ->add('tricks', TricksFormType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('images', TricksImagesFormType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('videos', TricksVideosFormType::class, [
                'label' => false,
                'required' => false
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get all data
            $data = $form->getData();
            $tricks = $data['tricks'];
            $videos = $data['videos'];
            $images = $data['images'];

            // Check if trick already exist
            $trickExist = $em->getRepository(Tricks::class)->findOneBy(['title' => $tricks->getTitle()]);
            if ($trickExist) {
                $this->addFlash('danger', "Le trick '{$tricks->getTitle()}' existe déjà");
                return $this->redirectToRoute('tricks_create');
            }

            // Add trick data
            $tricks->setAuthor($this->getUser());
            $tricks->setSlug($text->slugify($tricks->getTitle()));
            $tricks->setCreatedAt(new \DateTimeImmutable());
            $tricks->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($tricks);

            /* // Add images data
            if ($images) {
                $images->setTricks($tricks);
                $em->persist($images);
            }

            // Add videos data
            if ($videos) {
                $videos->setTricks($tricks);
                $em->persist($videos);
            } */

            // Save changes and redirect to homepage
            $em->flush();
            $this->addFlash('success', "Le trick '{$tricks->getTitle()}' a été crée avec succès");
            return $this->redirectToRoute('main');
        }
 
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
    public function update(
        Tricks $tricks, 
        Request $request,
        EntityManagerInterface $em): Response
    {
        // Check if user is author of the trick
        if ($tricks->getAuthor() !== $this->getUser()) {
            $this->addFlash('danger', 'Vous ne pouvez pas accéder à cette page');
            return $this->redirectToRoute('main');
        }

        $form = $this->createForm(TricksFormType::class, $tricks);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Check if trick already exist with the new title
            $trickExist = $em->getRepository(Tricks::class)->findOneBy(['title' => $tricks->getTitle()]);
            if ($trickExist && $trickExist->getId() !== $tricks->getId()) {
                $this->addFlash('danger', "Le trick '{$tricks->getTitle()}' existe déjà");
                return $this->redirectToRoute('tricks_update', ['slug' => $tricks->getSlug()]);
            }

            $tricks = $form->getData();
            $tricks->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($tricks);
            $em->flush();
            $this->addFlash('success', "Le trick '{$tricks->getTitle()}' a été modifié avec succès");
            return $this->redirectToRoute('tricks_details', ['slug' => $tricks->getSlug()]);
        }

        return $this->render('tricks/update.html.twig', [
            'tricks' => $tricks,
            'updateTricksForm' => $form->createView()
        ]);
    }

    #[Route('/{slug}/suppression', name: 'delete')]
    public function delete(
        Tricks $tricks, 
        EntityManagerInterface $em, 
        TricksVideosRepository $tricksVideos,
        TricksImagesRepository $tricksImages): Response
    {
        // Check if user is author of the trick
        if ($tricks->getAuthor() !== $this->getUser()) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer ce trick');
            return $this->redirectToRoute('tricks_details', ['slug' => $tricks->getSlug()]);
        }

        // Delete comments of the trick
        $comments = $tricks->getComments();
        foreach ($comments as $comment) {
            $em->remove($comment);
        }

        // Delete videos of the trick
        $videos = $tricksVideos->findByTricks($tricks->getId());
        foreach ($videos as $video) {
            $em->remove($video);
        }

        // Delete images of the trick
        $images = $tricksImages->findByTricks($tricks->getId());
        foreach ($images as $image) {
            $em->remove($image);
        }

        // Delete trick
        $em->remove($tricks);

        // Save changes
        $em->flush();

        $this->addFlash('success', "Le trick '{$tricks->getTitle()}' a été supprimé avec succès");
        return $this->redirectToRoute('main');
    }

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
        $this->addFlash('success', 'Le commentaire a été supprimé avec succès');
        return $this->redirectToRoute('tricks_details', ['slug' => $comments->getTrick()->getSlug()]);
    }
}
