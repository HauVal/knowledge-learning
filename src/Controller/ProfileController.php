<?php

namespace App\Controller;

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

    
}
