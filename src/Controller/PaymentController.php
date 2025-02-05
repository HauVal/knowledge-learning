<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Cursus;
use App\Entity\Lesson;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    #[Route('/create-checkout-session/{type}/{id}', name: 'create_checkout_session')]
    public function checkoutSession(
        string $type,
        int $id,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {
        Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        // Récupérer l'élément (Cursus ou Lesson)
        if ($type === 'cursus') {
            $item = $entityManager->getRepository(Cursus::class)->find($id);
        } else {
            $item = $entityManager->getRepository(Lesson::class)->find($id);
        }

        if (!$item) {
            return new JsonResponse(['error' => 'Article non trouvé'], 404);
        }

        // Créer la session de paiement Stripe
        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->getName(),
                    ],
                    'unit_amount' => $item->getPrice() * 100, // Stripe attend un montant en centimes
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $urlGenerator->generate('payment_success', [
                'type' => $type, 'id' => $id
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $urlGenerator->generate('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new JsonResponse(['id' => $checkoutSession->id]);
    }

    #[Route('/payment/success/{type}/{id}', name: 'payment_success')]
    public function paymentSuccess(string $type, int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }
    
        if ($type === 'cursus') {
            $item = $entityManager->getRepository(Cursus::class)->find($id);
            if ($item) {
                $user->addPurchasedCursus($item);
                foreach ($item->getLessons() as $lesson) {
                    $user->addPurchasedLesson($lesson);
                }
            }
        } else {
            $item = $entityManager->getRepository(Lesson::class)->find($id);
            if ($item) {
                $user->addPurchasedLesson($item);
            }
        }
    
        $entityManager->flush();
    
        $this->addFlash('success', 'Paiement réussi, accès débloqué !');
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function paymentCancel(): JsonResponse
    {
        return new JsonResponse(['message' => 'Paiement annulé']);
    }
}

