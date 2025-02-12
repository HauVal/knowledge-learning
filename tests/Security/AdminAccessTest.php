<?php

namespace App\Tests\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminAccessTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private static $client;

    protected function setUp(): void
    {
        self::$client = static::createClient();
        $this->entityManager = self::$client->getContainer()->get('doctrine')->getManager();
        $this->passwordHasher = self::$client->getContainer()->get(UserPasswordHasherInterface::class);

        // Vérifier si l'admin existe, sinon le créer
        $adminUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        if (!$adminUser) {
            $adminUser = new User();
            $adminUser->setEmail('admin@example.com');
            $adminUser->setName('Admin');
            $adminUser->setRoles(['ROLE_ADMIN']);
            $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, 'password123'));

            $this->entityManager->persist($adminUser);
            $this->entityManager->flush();
        }
    }

    public function testAdminAccessDeniedForUser(): void
    {
        self::$client->request('GET', '/admin');
        $this->assertResponseRedirects('/login');
    }

    public function testAdminAccessGrantedForAdmin(): void
    {
        $adminUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        $this->assertNotNull($adminUser, 'L\'utilisateur admin@example.com n\'a pas été trouvé.');

        self::$client->loginUser($adminUser);
        self::$client->request('GET', '/admin/');

        $this->assertResponseIsSuccessful();
    }
}




