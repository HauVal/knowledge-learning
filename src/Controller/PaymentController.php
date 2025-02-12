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

/**
 * Controller handling payment operations via Stripe.
 */
class PaymentController extends AbstractController
{
    /**
     * Creates a Stripe checkout session for purchasing a cursus or a lesson.
     * 
     * @param string $type The type of item to purchase ('cursus' or 'lesson').
     * @param int $id The ID of the item to purchase.
     * @param EntityManagerInterface $entityManager The entity manager to fetch item data.
     * @param UrlGeneratorInterface $urlGenerator The URL generator for success and cancel URLs.
     * 
     * @return JsonResponse Returns the Stripe checkout session ID.
     */
    #[Route('/create-checkout-session/{type}/{id}', name: 'create_checkout_session')]
    public function checkoutSession(
        string $type,
        int $id,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {
        Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        // Retrieve the item (either a Cursus or a Lesson) based on type
        $item = ($type === 'cursus')
            ? $entityManager->getRepository(Cursus::class)->find($id)
            : $entityManager->getRepository(Lesson::class)->find($id);

        // Return an error if the item is not found
        if (!$item) {
            return new JsonResponse(['error' => 'Item not found'], 404);
        }

        // Create a Stripe checkout session
        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->getName(),
                    ],
                    'unit_amount' => $item->getPrice() * 100, // Convert price to cents
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

    /**
     * Handles successful payment, granting access to the purchased cursus or lesson.
     * 
     * @param string $type The type of item purchased ('cursus' or 'lesson').
     * @param int $id The ID of the purchased item.
     * @param EntityManagerInterface $entityManager The entity manager to update user purchases.
     * 
     * @return Response Redirects to the theme page after granting access.
     */
    #[Route('/payment/success/{type}/{id}', name: 'payment_success')]
    public function paymentSuccess(string $type, int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        // Ensure the user is authenticated
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }
    
        // Process purchase based on type
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
    
        $this->addFlash('success', 'Payment successful, access granted!');
        return $this->redirectToRoute('app_theme_show', [
            'id' => $type === 'cursus' ? $item->getTheme()->getId() : $item->getCursus()->getTheme()->getId()
        ]);
        
    }

    /**
     * Handles payment cancellation and redirects the user.
     * 
     * @return Response Redirects the user back to their profile.
     */
    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function paymentCancel(): Response
    {
        return $this->redirectToRoute('app_profile');
    }
}
