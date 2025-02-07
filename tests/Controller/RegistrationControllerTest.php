<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testUserRegistration(): void
    {
        $client = static::createClient();

        // Accéder à la page d'inscription
        $crawler = $client->request('GET', '/');

        // Vérifier que la page s'affiche bien
        $this->assertResponseIsSuccessful();

        // Récupérer le formulaire
        $form = $crawler->selectButton('S\'inscrire')->form([
            'registration_form[name]' => 'Test User',
            'registration_form[email]' => 'test@example.com',
            'registration_form[password][first]' => 'TestPassword123!',
            'registration_form[password][second]' => 'TestPassword123!',
        ]);

        // Soumettre le formulaire
        $client->submit($form);

        // Vérifier la redirection après l'inscription
        $this->assertResponseRedirects('/email-sent');
        
        // Simuler la validation de l'email
        $client->request('GET', '/confirm-email/' . base64_encode('test@example.com'));

        // Vérifier que l'utilisateur est activé
        $this->assertTrue(
            self::getContainer()->get('doctrine')->getRepository(\App\Entity\User::class)->findOneBy(['email' => 'test@example.com'])->isActive()
        );
    }
}
