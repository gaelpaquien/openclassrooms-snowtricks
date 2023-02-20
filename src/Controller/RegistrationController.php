<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager,
        SendMailService $mail,
        JWTService $jwt,
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // Generate a JWT token
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            $payload = [
                'user_id' => $user->getId(),
                'exp' => time() + 3600
            ];

            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // Send mail
            $mail->send(
                'no-reply@snowtricks.com',
                $user->getEmail(),
                'Activation de votre compte Snowtricks',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
            );

            $this->addFlash('success', 'Votre compte a été enregistré et vous avez un reçu un email d\'activation');

            return $this->redirectToRoute('main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/activation/{token}', name: 'app_activation')]
    public function activation($token, JWTService $jwt, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        // Check if token is valid, is not expired and is not modified
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->checkSignature($token, $this->getParameter('app.jwtsecret'))) {
            $payload = $jwt->getPayload($token);
            $user = $userRepository->find($payload['user_id']);

            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'Votre compte est désormais activé');
                return $this->redirectToRoute('main');
            }

        }

        // If token is not valid, expired or modified
        $this->addFlash('danger', 'Le lien d\'activation est invalide ou a expiré');

        return $this->redirectToRoute('main');
    }

    #[Route('/renvoi-activation', name: 'app_activation_resend')]
    public function resendActivation(JWTService $jwt, SendMailService $mail, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('main');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Ce compte est déjà activé');
            return $this->redirectToRoute('main');
        }

        // Generate a JWT token
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $payload = [
            'user_id' => $user->getId(),
            'exp' => time() + 3600
        ];

        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // Send mail
        $mail->send(
            'no-reply@snowtricks.com',
            $user->getEmail(),
            'Activation de votre compte Snowtricks',
            'register',
            [
                'user' => $user,
                'token' => $token
            ]
        );

        $this->addFlash('success', 'Email d\'activation du compte envoyé');
        return $this->redirectToRoute('main');
    }
}
