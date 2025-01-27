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

class RegistrationController extends AbstractController
{
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
            // Hash le mot de passe
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // Par défaut, l'utilisateur n'est pas activé
            $user->setIsActive(false);

            // Sauvegarder l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            // Générer un lien de confirmation
            $confirmationUrl = $this->generateUrl('app_confirm_email', [
                'token' => base64_encode($user->getEmail()), // Simplicité pour l'exemple
            ], true);

            // Envoyer un email de confirmation
            $email = (new Email())
                ->from('no-reply@knowledge-learning.com')
                ->to($user->getEmail())
                ->subject('Confirmation de votre inscription')
                ->html("<p>Merci de vous inscrire. Cliquez sur le lien suivant pour activer votre compte :</p>
                        <a href='{$confirmationUrl}'>Confirmer mon compte</a>");

            $mailer->send($email);

            $this->addFlash('success', 'Un email de confirmation vous a été envoyé.');
            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/confirm-email/{token}', name: 'app_confirm_email')]
    public function confirmEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        $email = base64_decode($token);
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $user->setIsActive(true);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a été activé avec succès.');
        return $this->redirectToRoute('app_login'); // Rediriger vers la connexion
    }
}

