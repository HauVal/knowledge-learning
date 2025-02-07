<?php

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $passwordHasher;

    protected function setUp(): void
    {
        parent::setUp();

        // Création du client HTTP après le kernel
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        // Nettoyage de la base de test
        $this->entityManager->createQuery('DELETE FROM App\Entity\User')->execute();

        // Création d'un utilisateur test
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setName('Test User');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'TestPassword123!'));
        $user->setRoles(['ROLE_USER']);
        $user->setIsActive(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function testSuccessfulLogin(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'test@example.com',
            'password' => 'TestPassword123!',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/profile');
    }

    public function testLoginWithWrongPassword(): void
    {
        $crawler = $this->client->request('GET', '/login');
    
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'test@example.com',
            'password' => 'WrongPassword',
        ]);
    
        $this->client->submit($form);
    }
}

