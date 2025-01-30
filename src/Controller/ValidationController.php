<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Cursus;
use App\Entity\User;
use App\Entity\Certification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ValidationController extends AbstractController
{
    #[Route('/validate/lesson/{id}', name: 'validate_lesson')]
    #[IsGranted('ROLE_USER')]
    public function validateLesson(Lesson $lesson, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            throw $this->createAccessDeniedException();
        }

        if (!$user->getValidatedLessons()->contains($lesson)) {
            $user->addValidatedLesson($lesson);
        }

        // Vérifier si toutes les leçons du cursus sont validées
        $cursus = $lesson->getCursus();
        if ($cursus) {
            $allLessonsValidated = true;
            foreach ($cursus->getLessons() as $cursusLesson) {
                if (!$user->getValidatedLessons()->contains($cursusLesson)) {
                    $allLessonsValidated = false;
                    break;
                }
            }

            if ($allLessonsValidated) {
                $user->addValidatedCursus($cursus);

                // Vérifier si l'utilisateur n'a pas déjà la certification
                $existingCertification = $entityManager->getRepository(Certification::class)
                    ->findOneBy(['user' => $user, 'cursus' => $cursus]);

                if (!$existingCertification) {
                    $certification = new Certification();
                    $certification->setUser($user);
                    $certification->setCursus($cursus);
                    $certification->setObtainedAt(new \DateTimeImmutable());

                    $entityManager->persist($certification);
                }
            }
        }

        $entityManager->flush();
        $this->addFlash('success', 'Leçon validée avec succès !');

        return $this->redirectToRoute('app_lesson_show', ['id' => $lesson->getId()]);
    }
}
