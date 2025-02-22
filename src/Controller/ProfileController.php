<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted as AttributeIsGranted;

/**
 * Controller for managing user profile-related actions.
 */
class ProfileController extends AbstractController
{
    /**
     * Displays the user profile page with available themes.
     *
     * @param ThemeRepository $themeRepository Repository for retrieving themes.
     * @return Response The rendered profile page.
     */
    #[Route('/profile', name: 'app_profile')]
    #[AttributeIsGranted('ROLE_USER')] // Restrict access to authenticated users only
    public function index(ThemeRepository $themeRepository): Response
    {
        $themes = $themeRepository->findAll();

        return $this->render('profile/index.html.twig', [
            'themes' => $themes,
        ]);
    }

    /**
     * Displays details of a specific theme.
     *
     * @param int $id The ID of the theme.
     * @param ThemeRepository $themeRepository Repository for retrieving themes.
     * @return Response The rendered theme detail page.
     */
    #[Route('/theme/{id}', name: 'app_theme_show')]
    #[AttributeIsGranted('ROLE_USER')]
    public function showTheme(int $id, ThemeRepository $themeRepository): Response
    {
        $theme = $themeRepository->find($id); // Retrieve the theme by ID
    
        if (!$theme) {
            throw $this->createNotFoundException('Thème non trouvé.');
        }
    
        return $this->render('profile/theme_show.html.twig', [
            'theme' => $theme,
            'stripe_public_key' => $this->getParameter('stripe_public_key'), // Pass Stripe public key for payments
        ]);
    }

    /**
     * Displays the user's certifications.
     *
     * @return Response The rendered certifications page.
     */
    #[Route('/certifications', name: 'app_certifications')]
    #[AttributeIsGranted('ROLE_USER')]
    public function certifications(): Response
    {
        $user = $this->getUser();
    
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur non trouvé.');
        }
    
        return $this->render('profile/certifications.html.twig', [
            'certifications' => $user->getCertifications(),
        ]);
    }
}