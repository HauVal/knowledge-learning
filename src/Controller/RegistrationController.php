<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller responsible for user registration and email confirmation.
 */
class RegistrationController extends AbstractController
{
    /**
     * Handles user registration.
     *
     * This method displays the registration form and processes the submitted data.
     * If the form is valid, it hashes the user's password, sets the user as inactive,
     * saves the user in the database, generates a confirmation link, and sends a confirmation email.
     *
     * @param Request $request The HTTP request object.
     * @param UserPasswordHasherInterface $passwordHasher The password hasher service.
     * @param EntityManagerInterface $entityManager The entity manager for database operations.
     * @param MailerInterface $mailer The mailer service for sending confirmation emails.
     * @return Response The rendered registration form or a redirection after form submission.
     */
    #[Route('/', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the user's password
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // Set user as inactive by default
            $user->setIsActive(false);

            $entityManager->persist($user);
            $entityManager->flush();

            // Generate a confirmation link
            $confirmationUrl = $this->generateUrl('app_confirm_email', [
                'token' => base64_encode($user->getEmail()),
            ], true);

            // Send a confirmation email
            $email = (new Email())
                ->from('no-reply@knowledge-learning.com')
                ->to($user->getEmail())
                ->subject('Confirmation de votre inscription')
                ->html("<p>Bienvenue sur Knowledge-learning. Pour activer votre compte, cliquez sur le lien :</p>
                        <a href='http://127.0.0.1:8000{$confirmationUrl}'>Confirmer mon compte</a>");

            $mailer->send($email);

            return $this->redirectToRoute('app_email_sent');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Confirms a user's email address.
     *
     * This method retrieves the user by decoding the email token, activates their account,
     * and redirects them to the login page.
     *
     * @param string $token The base64-encoded email token.
     * @param EntityManagerInterface $entityManager The entity manager for database operations.
     * @return Response A redirection to the login page after activation.
     */
    #[Route('/confirm-email/{token}', name: 'app_confirm_email')]
    public function confirmEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        // Decode the token to get the email
        $email = base64_decode($token);
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Activate the user account
        $user->setIsActive(true);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a été activé avec succès.');
        return $this->redirectToRoute('app_login'); 
    }

    /**
     * Displays a page confirming that a confirmation email has been sent.
     *
     * This page informs users that they need to check their email for a confirmation link.
     *
     * @return Response The rendered confirmation page.
     */
    #[Route('/email-sent', name: 'app_email_sent')]
    public function emailSent(): Response
    {
        return $this->render('registration/email_sent.html.twig');
    }
}

