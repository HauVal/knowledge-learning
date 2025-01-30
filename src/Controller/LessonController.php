<?php

namespace App\Controller;

use App\Entity\Lesson;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LessonController extends AbstractController
{
    #[Route('/lesson/{id}', name: 'app_lesson_show')]
    #[IsGranted('ROLE_USER')]
    public function showLesson(Lesson $lesson): Response
    {
        return $this->render('lesson/index.html.twig', [
            'lesson' => $lesson,
        ]);
    }
}
