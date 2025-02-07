<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Entity\Cursus;
use App\Entity\Lesson;
use Doctrine\ORM\EntityManagerInterface;

class PurchaseTest extends WebTestCase
{
    public function testBuyCursus(): void
    {

        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $user = new User();
        $user->setEmail('test@example.com');
        $user->setName('Test User');
        $user->setRoles(['ROLE_USER']);
        $user->setIsActive(true);
        $hashedPassword = $entityManager->getContainer()->get('security.password_hasher')->hashPassword($user, 'password123');
        $user->setPassword($hashedPassword);
        
        $entityManager->persist($user);
        $entityManager->flush();
        
        $client->loginUser($user);

        // Vérifier si un Cursus existe
        $cursus = $entityManager->getRepository(Cursus::class)->findOneBy([]);
        $this->assertNotNull($cursus, "Aucun Cursus trouvé en base.");

        // Effectuer la requête d'achat
        $client->request('GET', "/buy/cursus/" . $cursus->getId());
        $this->assertResponseRedirects('/profile');
    }

    public function testBuyLesson(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

$user = new User();
$user->setEmail('test@example.com');
$user->setName('Test User');
$user->setRoles(['ROLE_USER']);
$user->setIsActive(true);
$hashedPassword = $entityManager->getContainer()->get('security.password_hasher')->hashPassword($user, 'password123');
$user->setPassword($hashedPassword);

$entityManager->persist($user);
$entityManager->flush();

$client->loginUser($user);

        // Vérifier si une Leçon existe
        $lesson = $entityManager->getRepository(Lesson::class)->findOneBy([]);
        $this->assertNotNull($lesson, "Aucune Leçon trouvée en base.");

        // Effectuer la requête d'achat
        $client->request('GET', "/buy/lesson/" . $lesson->getId());
        $this->assertResponseRedirects('/profile');
    }
}
