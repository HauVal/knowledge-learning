<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted as AttributeIsGranted;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[AttributeIsGranted('ROLE_USER')]
    public function index(ThemeRepository $themeRepository): Response
    {
        $themes = $themeRepository->findAll();

        return $this->render('profile/index.html.twig', [
            'themes' => $themes,
        ]);
    }

    #[Route('/theme/{id}', name: 'app_theme_show')]
    #[AttributeIsGranted('ROLE_USER')]
    public function showTheme(int $id, ThemeRepository $themeRepository): Response
    {
        $theme = $themeRepository->find($id);
    
        if (!$theme) {
            throw $this->createNotFoundException('Thème non trouvé.');
        }
    
        return $this->render('profile/theme_show.html.twig', [
            'theme' => $theme,
        ]);
    }

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
