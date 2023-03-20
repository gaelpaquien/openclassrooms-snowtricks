<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Entity\TrickVideo;
use App\Form\CreateCommentFormType;
use App\Form\TrickFormType;
use App\Form\TrickImageFormType;
use App\Form\TrickVideoFormType;
use App\Repository\CommentRepository;
use App\Repository\TrickImageRepository;
use App\Repository\TrickVideoRepository;
use App\Service\ImageService;
use App\Service\Text\SlugService;
use App\Service\Text\URLService;
use App\Service\TrickService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        SlugService $text,
        ImageService $imageService,
        URLService $urlService,
        TrickService $trickService): Response
    {
        // Check if user is logged
        if (!$trickService->userIsLogged()) {
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

            // Images
            $images = $form->get('image')->getData();
            foreach ($images as $image) {
                // Destination folder
                $folder = 'tricks';
                // Save image in folder
                $file = $imageService->add($image, $folder, 300, 300);

                $image = new TrickImage();
                $image->setName($file);
                $trick->addImage($image);
            }

            // Videos
            $videos = $form->get('videos')->getData();
            if ($videos) {
                foreach ($videos as $video) {
                    if ($video !== null && is_string($video)) {
                        // Construct embed url
                        $url = $urlService->constructEmbedUrl($urlService->getUrlInfos($video));

                        // Check if embed url is valid
                        if (!$urlService->isEmbedUrlValid($url)) {
                            $this->addFlash('danger', "'{$video}' n'est pas une URL de vidéo valide");
                            return $this->redirectToRoute('trick_create');
                        }

                        $video = new TrickVideo();
                        $video->setUrl($url);
                        $trick->addVideo($video);
                    }
                }
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
            'trickForm' => $form->createView()
        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(
        Trick $trick,
        CommentRepository $commentRepository,
        Request $request,
        EntityManagerInterface $em,
        TrickService $trickService): Response
    {
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
                'isAdmin' => $trickService->userIsAdmin()
            ]);
        }

        return $this->render('trick/details.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'createCommentForm' => $form->createView(),
            'isAdmin' => $trickService->userIsAdmin()
        ]);
    }

    #[Route('/{slug}/edition', name: 'update')]
    public function update(
        Trick $trick,
        Request $request,
        EntityManagerInterface $em,
        ImageService $imageService,
        URLService $urlService,
        TrickService $trickService): Response
    {
        // Check if user is author of the trick or if user is admin
        if (!$trickService->userIsAuthor($trick) && !$trickService->userIsAdmin()) {
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
                return $this->redirectToRoute('trick_update', [
                    'slug' => $trick->getSlug()
                ]);
            }

            // Images
            $images = $form->get('image')->getData();
            foreach ($images as $image) {
                // Destination folder
                $folder = 'tricks';
                // Save image in folder
                $file = $imageService->add($image, $folder, 300, 300);

                // Add image
                $image = new TrickImage();
                $image->setName($file);
                $trick->addImage($image);
            }

            // Videos
            $videos = $form->get('videos')->getData();
            if ($videos) {
                foreach ($videos as $video) {
                    if ($video !== null && is_string($video)) {
                        // Construct embed url
                        $url = $urlService->constructEmbedUrl($urlService->getUrlInfos($video));

                        // Check if embed url is valid
                        if (!$urlService->isEmbedUrlValid($url)) {
                            $this->addFlash('danger', "'{$video}' n'est pas une URL de vidéo valide");
                            return $this->redirectToRoute('trick_update', [
                                'slug' => $trick->getSlug()
                            ]);
                        }

                        $video = new TrickVideo();
                        $video->setUrl($url);
                        $trick->addVideo($video);
                    }
                }
            }

            // Update trick data
            $trick->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($trick);

            // Save and redirect
            $em->flush();
            $this->addFlash('success', "Le trick '{$trick->getTitle()}' a été modifié avec succès");
            return $this->redirectToRoute('trick_update', [
                'slug' => $trick->getSlug(),
                'isAdmin' => $trickService->userIsAdmin()
            ]);
        }

        // Form to update image
        $formNewImage = $this->createForm(TrickImageFormType::class);
        $formNewImage->handleRequest($request);

        if ($formNewImage->isSubmitted() && $formNewImage->isValid()) {
            $newImage = $formNewImage->get('newImage')->getData();
            $oldImage = $formNewImage->get('oldImage')->getData();
            $trickId = $formNewImage->get('trickId')->getData();

            // Check if newImage is valid image
            if ($newImage === null) {
                $this->addFlash('danger', "Vous devez sélectionner une nouvelle image");
                return $this->redirectToRoute('trick_update', [
                    'slug' => $trick->getSlug()
                ]);
            }

            $trickImage = $em->getRepository(TrickImage::class)->findOneBy([
                'name' => $oldImage,
                'trick' => $trickId
            ]);

            // Check if oldImage exist and is associated with the trick
            if (!$trickImage) {
                $this->addFlash('danger', "L'image n'existe pas");
                return $this->redirectToRoute('trick_update', [
                    'slug' => $trick->getSlug()
                ]);
            }

            // Check if oldImage is not the default image
            if ($oldImage === 'default.png') {
                $this->addFlash('danger', "L'image par défaut ne peut pas être supprimée");
                return $this->redirectToRoute('trick_update', [
                    'slug' => $trick->getSlug()
                ]);
            }

            // Delete old image
            $imageService->delete($oldImage, 'tricks', 300, 300);
            $em->remove($trickImage);

            // Add new image
            $file = $imageService->add($newImage, 'tricks', 300, 300);
            $image = new TrickImage();
            $image->setName($file);
            $trick->addImage($image);
            $em->persist($trick);

            // Save changes and redirect
            $em->flush();
            $this->addFlash('success', "L'image a été modifiée avec succès");
            return $this->redirectToRoute('trick_update', [
                'slug' => $trick->getSlug()
            ]);
        }

        // Form to update video
        $formNewVideo = $this->createForm(TrickVideoFormType::class);
        $formNewVideo->handleRequest($request);

        if ($formNewVideo->isSubmitted() && $formNewVideo->isValid()) {
            $newVideo = $formNewVideo->get('newVideo')->getData();
            $oldVideo = $formNewVideo->get('oldVideo')->getData();
            $trickId = $formNewVideo->get('trickId')->getData();

            // Check if newVideo is valid video
            if ($newVideo === null) {
                $this->addFlash('danger', "Vous devez sélectionner une nouvelle vidéo");
                return $this->redirectToRoute('trick_update', [
                    'slug' => $trick->getSlug()
                ]);
            }

            $trickVideo = $em->getRepository(TrickVideo::class)->findOneBy([
                'url' => $oldVideo,
                'trick' => $trickId
            ]);

            // Check if oldVideo exist and is associated with the trick
            if (!$trickVideo) {
                $this->addFlash('danger', "La vidéo n'existe pas");
                return $this->redirectToRoute('trick_update', [
                    'slug' => $trick->getSlug()
                ]);
            }

            // Construct embed url
            $url = $urlService->constructEmbedUrl($urlService->getUrlInfos($newVideo));

            // Check if embed url is valid
            if (!$urlService->isEmbedUrlValid($url)) {
                $this->addFlash('danger', "'{$newVideo}' n'est pas une URL de vidéo valide");
                return $this->redirectToRoute('trick_update', [
                    'slug' => $trick->getSlug()
                ]);
            }

            // Update video
            $trickVideo->setUrl($url);
            $em->persist($trickVideo);

            // Save changes and redirect
            $em->flush();
            $this->addFlash('success', "La vidéo a été modifiée avec succès");
            return $this->redirectToRoute('trick_update', [
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->render('trick/update.html.twig', [
            'trick' => $trick,
            'trickForm' => $form->createView(),
            'trickImageForm' => $formNewImage->createView(),
            'trickVideoForm' => $formNewVideo->createView(),
            'isAdmin' => $trickService->userIsAdmin()
        ]);
    }

    #[Route('/{slug}/suppression', name: 'delete')]
    public function delete(
        Trick $trick,
        EntityManagerInterface $em,
        TrickVideoRepository $trickVideo,
        TrickImageRepository $trickImage,
        ImageService $imageService,
        TrickService $trickService): Response
    {

        // Check if user is author of the trick and if user is admin
        if (!$trickService->userIsAuthor($trick) && !$trickService->userIsAdmin()) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer ce trick');
            return $this->redirectToRoute('trick_details', [
                'slug' => $trick->getSlug(),
                'isAdmin' => $trickService->userIsAdmin()
            ]);
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
            // Check if image exist
            if ($imageService->delete($image->getName(), 'tricks', 300, 300)) {
                // Delete image
                $em->remove($image);
                $em->flush();
            }
        }

        // Delete trick
        $em->remove($trick);

        // Save and redirect
        $em->flush();
        $this->addFlash('success', "Le trick '{$trick->getTitle()}' a été supprimé avec succès");
        return $this->redirectToRoute('main', [
            'isAdmin' => $trickService->userIsAdmin()
        ]);
    }

    #[Route('/{slug}/commentaire/{id}/suppression', name: 'delete_comment')]
    public function deleteComment(
        Comment $comment,
        EntityManagerInterface $em,
        TrickService $trickService): Response
    {
        // Check if user is author of the comment and if user is admin
        if (!$trickService->userIsAuthor($comment) && !$trickService->userIsAdmin()) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer ce commentaire');
            return $this->redirectToRoute('trick_details', [
                'slug' => $comment->getTrick()->getSlug(),
                'isAdmin' => $trickService->userIsAdmin()
            ]);
        }

        // Delete comment
        $em->remove($comment);

        // Save and redirect
        $em->flush();
        $this->addFlash('success', 'Le commentaire a été supprimé avec succès');
        return $this->redirectToRoute('trick_details', [
            'slug' => $comment->getTrick()->getSlug(),
            'isAdmin' => $trickService->userIsAdmin()
        ]);
    }

    #[Route('/{slug}/image/{id}/suppression', name: 'image_delete', methods: ['DELETE'])]
    public function deleteImage(
        TrickImage $image,
        EntityManagerInterface $em,
        Request $request,
        ImageService $imageService,
        TrickService $trickService): JsonResponse
    {
        // Check if user is author of the trick and if user is admin
        if ($image->getTrick()->getAuthor() !== $this->getUser() && !$trickService->userIsAdmin()) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer cette image');
            return $this->redirectToRoute('trick_details', [
                'slug' => $image->getTrick()->getSlug(),
                'isAdmin' => $trickService->userIsAdmin()
            ]);
        }

        // Get data from request
        $data = json_decode($request->getContent(), true);

        // Check if token is valid
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // Check if image exist
            if ($imageService->delete($image->getName(), 'tricks', 300, 300)) {
                // Delete image
                $em->remove($image);
                $em->flush();
                return new JsonResponse(['success' => true], 200);
            }
            return new JsonResponse(['error' => 'Erreur lors de la suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }

    #[Route('/{slug}/video/{id}/suppression', name: 'video_delete', methods: ['DELETE'])]
    public function deleteVideo(
        TrickVideo $video,
        EntityManagerInterface $em,
        Request $request,
        TrickService $trickService): JsonResponse
    {
        // Check if user is author of the trick and if user is admin
        if ($video->getTrick()->getAuthor() !== $this->getUser() && !$trickService->userIsAdmin()) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer cette vidéo');
            return $this->redirectToRoute('trick_details', [
                'slug' => $video->getTrick()->getSlug(),
                'isAdmin' => $trickService->userIsAdmin()
            ]);
        }

        // Get data from request
        $data = json_decode($request->getContent(), true);

        // Check if token is valid
        if ($this->isCsrfTokenValid('delete' . $video->getId(), $data['_token'])) {
            // Delete video
            $em->remove($video);
            $em->flush();
            return new JsonResponse(['success' => true], 200);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }
}
