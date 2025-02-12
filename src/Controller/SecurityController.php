<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller handling authentication processes such as login and logout.
 */
class SecurityController extends AbstractController
{
    /**
     * Handles the user login process.
     *
     * @param AuthenticationUtils $authenticationUtils Provides utilities for retrieving authentication errors and the last entered username.
     * @return Response Renders the login page with any authentication errors and the last entered username.
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // Retrieve the last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Render the login page with the last entered username and any login error
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Handles user logout.
     *
     * This method is intercepted by Symfony's security system and does not require implementation.
     * 
     * @throws \LogicException This method should not be executed directly.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // This method is intercepted by Symfony's security system
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}


