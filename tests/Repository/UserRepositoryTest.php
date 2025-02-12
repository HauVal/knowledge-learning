<?php

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Repository\UserRepository;

class UserRepositoryTest extends KernelTestCase
{
    public function testFindByEmail(): void
    {
        self::bootKernel();
        $userRepository = self::getContainer()->get(UserRepository::class);


        $user = $userRepository->findOneBy(['email' => 'test@example.com']);

        $this->assertNotNull($user);
        $this->assertEquals('test@example.com', $user->getEmail());
    }
}
