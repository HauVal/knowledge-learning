<?php

namespace App\Controller;

use App\Entity\Cursus;
use App\Entity\Lesson;
use App\Entity\User;
use App\Entity\Theme;
use App\Form\CursusType;
use App\Form\LessonType;
use App\Form\ThemeType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\UserRepository;
use App\Repository\ThemeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/users', name: 'admin_users')]
    public function manageUsers(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/content', name: 'admin_content')]
    public function manageContent(ThemeRepository $themeRepository): Response
    {
        return $this->render('admin/content.html.twig', [
            'themes' => $themeRepository->findAll(),
        ]);
    }

    #[Route('/purchases', name: 'admin_purchases')]
    public function managePurchases(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
    
        return $this->render('admin/purchases.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/content/add-theme', name: 'admin_add_theme')]
    #[Route('/content/edit-theme/{id}', name: 'admin_edit_theme')]
    public function manageTheme(Request $request, EntityManagerInterface $entityManager, Theme $theme = null): Response
    {
        if (!$theme) {
            $theme = new Theme();
        }
    
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($theme);
            $entityManager->flush();
    
            return $this->redirectToRoute('admin_content');
        }
    
        return $this->render('admin/theme_form.html.twig', [
            'form' => $form->createView(),
            'theme' => $theme,
        ]);
    }

    #[Route('/content/delete-theme/{id}', name: 'admin_delete_theme', methods: ['POST'])]
    public function deleteTheme(Theme $theme, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($theme);
        $entityManager->flush();
    
        $this->addFlash('success', 'Thème supprimé avec succès !');
        return $this->redirectToRoute('admin_content');
    }

    #[Route('/content/add-cursus', name: 'admin_add_cursus')]
    #[Route('/content/edit-cursus/{id}', name: 'admin_edit_cursus')]
    public function manageCursus(Request $request, EntityManagerInterface $entityManager, Cursus $cursus = null): Response
    {
        if (!$cursus) {
            $cursus = new Cursus();
        }
    
        $form = $this->createForm(CursusType::class, $cursus);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cursus);
            $entityManager->flush();
    
            return $this->redirectToRoute('admin_content');
        }
    
        return $this->render('admin/cursus_form.html.twig', [
            'form' => $form->createView(),
            'cursus' => $cursus,
        ]);
    }

    #[Route('/content/delete-cursus/{id}', name: 'admin_delete_cursus', methods: ['POST'])]
    public function deleteCursus(Cursus $cursus, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($cursus);
        $entityManager->flush();
    
        $this->addFlash('success', 'Cursus supprimé avec succès !');
        return $this->redirectToRoute('admin_content');
    }

    #[Route('/content/add-lesson', name: 'admin_add_lesson')]
    #[Route('/content/edit-lesson/{id}', name: 'admin_edit_lesson')]
    public function manageLesson(Request $request, EntityManagerInterface $entityManager, Lesson $lesson = null): Response
    {
        if (!$lesson) {
            $lesson = new Lesson();
        }
    
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lesson);
            $entityManager->flush();
    
            return $this->redirectToRoute('admin_content');
        }
    
        return $this->render('admin/lesson_form.html.twig', [
            'form' => $form->createView(),
            'lesson' => $lesson,
        ]);
    }

    #[Route('/content/delete-lesson/{id}', name: 'admin_delete_lesson', methods: ['POST'])]
    public function deleteLesson(Lesson $lesson, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($lesson);
        $entityManager->flush();
    
        $this->addFlash('success', 'Leçon supprimée avec succès !');
        return $this->redirectToRoute('admin_content');
    }

    #[Route('/users/add', name: 'admin_add_user')]
    #[Route('/users/edit/{id}', name: 'admin_edit_user')]
    public function manageUser(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        User $user = null
    ): Response {
        if (!$user) {
            $user = new User();
            $user->setIsActive(true);
        }
    
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            // Hash the password if a new one is provided
            $plainPassword = $form->get('password')->getData();
            if (!empty($plainPassword)) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }
    
            $selectedRole = $form->get('roles')->getData();
            $user->setRoles([$selectedRole]);
    
            $entityManager->persist($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'Utilisateur ajouté/modifié avec succès !');
            return $this->redirectToRoute('admin_users');
        }
    
        return $this->render('admin/user_form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/users/delete/{id}', name: 'admin_delete_user', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($user);
        $entityManager->flush();
    
        $this->addFlash('success', 'Utilisateur supprimé avec succès !');
        return $this->redirectToRoute('admin_users');
    }
}
