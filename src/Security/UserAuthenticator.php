<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function authenticate(Request $request): Passport
    {
        // Retrieve email, password, and CSRF token from the login form
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $csrfToken = $request->request->get('_csrf_token');
    
        // Store the last entered username in session
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);
    
        return new Passport(
            new UserBadge($email, function ($userIdentifier) use ($password) {
                // Find user by email
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);
    
                // If user does not exist, throw an error
                if (!$user) {
                    throw new CustomUserMessageAuthenticationException('Email incorrect.');
                }

                // Validate the provided password against the stored hash
                if (!$this->passwordHasher->isPasswordValid($user, $password)) {
                    throw new CustomUserMessageAuthenticationException('Mot de passe incorrect.');
                }
    
                // Check if the user account is activated
                if (!$user->isActive()) {
                    throw new CustomUserMessageAuthenticationException('Votre compte n\'est pas encore activé. Veuillez vérifier votre email.');
                }
    
                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Get the authenticated user
        $user = $token->getUser();
    
        // If the user is an admin, redirect to the admin dashboard
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
        }
    
        // Otherwise, redirect to the user profile page
        return new RedirectResponse($this->urlGenerator->generate('app_profile')); 
    }

    protected function getLoginUrl(Request $request): string
    {
        // Return the login page URL
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}

