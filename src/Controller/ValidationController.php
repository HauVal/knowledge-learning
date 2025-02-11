<?php

namespace App\Controller;

use App\Entity\Lesson;
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
        // Get the currently authenticated user
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            throw $this->createAccessDeniedException();
        }

        // Check if the lesson is not already validated for the user
        if (!$user->getValidatedLessons()->contains($lesson)) {
            $user->addValidatedLesson($lesson);
        }

        // Check if all lessons of the cursus are validated
        $cursus = $lesson->getCursus();
        if ($cursus) {
            $allLessonsValidated = true;
            foreach ($cursus->getLessons() as $cursusLesson) {
                if (!$user->getValidatedLessons()->contains($cursusLesson)) {
                    $allLessonsValidated = false;
                    break;
                }
            }

            // If all lessons are validated, validate the cursus
            if ($allLessonsValidated) {
                $user->addValidatedCursus($cursus);

                // Check if the user already has the certification for this cursus
                $existingCertification = $entityManager->getRepository(Certification::class)
                    ->findOneBy(['user' => $user, 'cursus' => $cursus]);

                // If no certification exists, create a new one
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
