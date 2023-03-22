<?php

namespace App\Controller;

use App\Form\Security\ResetPasswordFormType;
use App\Form\Security\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('main');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/mot-de-passe-oublie', name: 'app_forgot_password')]
    public function forgotPassword(
        Request $request,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        SendMailService $mail): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Find user by username
            $user = $userRepository->findOneBy([
                'username' => $form->get('username')->getData()
            ]);

            // If user exists
            if ($user) {
                // Generate token
                $token = $tokenGenerator->generateToken();

                // Set token to user and save it
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // Generate url to reset password
                $url = $this->generateUrl('app_reset_password', [
                    'token' => $token
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                // Send email to user
                $context = [
                    'url' => $url,
                    'user' => $user
                ];
                $mail->send(
                    'no-reply@snowtricks.com',
                    $user->getEmail(),
                    'Réinitialisation de votre mot de passe',
                    'reset_password',
                    $context
                );

                $this->addFlash('success', 'Un email vous a été envoyé pour réinitialiser votre mot de passe');
                return $this->redirectToRoute('app_login');
            }

            // If user doesn't exist
            $this->addFlash('danger', 'Ce nom d\'utilisateur n\'existe pas');
            return $this->redirectToRoute('app_forgot_password');
        }

        return $this->render('security/forgot_password.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route(path: '/reinitialisation-du-mot-de-passe/{token}', name: 'app_reset_password')]
    public function resetPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher): Response
    {
        // Check if token is valid
        $user = $userRepository->findOneBy([
            'resetToken' => $token
        ]);

        // If token exists
        if ($user) {
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Set token to null
                $user->setResetToken("");

                // Set new password
                $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));

                // Save user
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été modifié');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }

        // If token doesn't exist
        $this->addFlash('danger', 'Le lien de réinitialisation n\'est pas valide');
        return $this->redirectToRoute('app_login');
    }
}
