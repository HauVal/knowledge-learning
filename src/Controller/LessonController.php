<?php

namespace App\Controller;

use App\Entity\Lesson;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller for handling lessons.
 */
class LessonController extends AbstractController
{
    /**
     * Displays a specific lesson.
     *
     * This route is restricted to authenticated users.
     *
     * @param Lesson $lesson The lesson entity to display.
     * @return Response The rendered lesson page.
     */
    #[Route('/lesson/{id}', name: 'app_lesson_show')]
    #[IsGranted('ROLE_USER')]
    public function showLesson(Lesson $lesson): Response
    {
        return $this->render('lesson/index.html.twig', [
            'lesson' => $lesson,
        ]);
    }
}


