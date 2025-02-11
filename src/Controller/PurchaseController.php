<?php

namespace App\Controller;

use App\Entity\Cursus;
use App\Entity\Lesson;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PurchaseController extends AbstractController
{
    #[Route('/buy/cursus/{id}', name: 'buy_cursus')]
    #[IsGranted('ROLE_USER')] // Restrict access to authenticated users only
    public function buyCursus(Cursus $cursus, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        // Check if the user already owns this Cursus
        if (!$user->getPurchasedCursuses()->contains($cursus)) {
            $user->addPurchasedCursus($cursus);

            // Unlock all lessons of the Cursus
            foreach ($cursus->getLessons() as $lesson) {
                $user->addPurchasedLesson($lesson);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Cursus acheté avec succès !');
        }

        return $this->redirectToRoute('app_theme_show', ['id' => $cursus->getTheme()->getId()]);
    }

    #[Route('/buy/lesson/{id}', name: 'buy_lesson')]
    #[IsGranted('ROLE_USER')]
    public function buyLesson(Lesson $lesson, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        // Check if the user already owns this Lesson
        if (!$user->getPurchasedLessons()->contains($lesson)) {
            $user->addPurchasedLesson($lesson);
            $entityManager->flush();
            $this->addFlash('success', 'Leçon achetée avec succès !');
        }

        return $this->redirectToRoute('app_theme_show', ['id' => $lesson->getCursus()->getTheme()->getId()]);
    }
}